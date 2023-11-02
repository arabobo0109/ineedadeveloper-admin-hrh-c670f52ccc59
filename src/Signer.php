<?php 
namespace Simcify;

require_once 'TCPDF/tcpdf.php';
require_once 'TCPDF/tcpdi.php';

use Simcify\Models\StudentModel;

class Signer {
    
    /**
     * Upload file
     * 
     * @param   array $data
     * @return  true
     */
    public static function upload($data) {
        $files = Database::table("files")->company()->where("name", $data["name"])->where("folder", $data["folder"])->first();
        if (!empty($files) && $data["source"] == "form") {
            return responder("error", "Already Exists!", "File name '".$data["name"]."' already exists.");
        }

        $allowedExtensions = "pdf";

        $upload = File::upload(
            $data['file'],
            "files",
            array(
                "source" => $data['source'],
                "allowedExtensions" => $allowedExtensions
            )
        );

        if ($upload['status'] == "success") {
            self::keepcopy($upload['info']['name']);
            $data["filename"] = $upload['info']['name'];
            $data["size"] = $upload['info']['size'];
            $data["extension"] = $upload['info']['extension'];
            $data['company']=Auth::cid();

            $activity = $data['activity'];
            unset($data['file'], $data['source'], $data['activity']);
            Database::table("files")->insert($data);
            Database::table("history")->insert(array("file" => $data["document_key"], "activity" => $activity, "type" => "default"));
            return responder("success", "Upload Complete", "File successfully uploaded.");
        }else{
            return responder("error", "Oops!", $upload['message']);
        }
    }
    
    /**
     * Duplicate file
     * 
     * @param   int $data
     * @return  true
     */
    public static function duplicate($document, $duplicateName = '',$fileName='') {
    	$user = Auth::user();
    	if (empty($fileName)){
            $fileName = Str::random(10).".".$document->extension;
        }
        copy(config("app.storage")."files/".$document->filename, config("app.storage")."files/".$fileName);
//        self::keepcopy($fileName);
        if(empty($duplicateName)){
            $duplicateName = $document->name." (Copy)";
        }
        $activity = 'File Duplicated from '.escape($document->name).' by <span class="text-primary">'.escape($user->fname.' '.$user->lname).'</span>.';
        $data = array(
                        "uploaded_by" => $user->id,
                        "name" => $duplicateName,
                        "folder" => $document->folder,
                        "filename" => $fileName,
                        "extension" => $document->extension,
                        "size" => $document->size,
                        "status" => $document->status,
                        "is_template" => $document->is_template,
                        "template_fields" => $document->template_fields,
                        "document_key" => Str::random(32),
                        'company' => Auth::cid()
                    );
        Database::table("files")->insert($data);
        $documentId = Database::table("files")->insertId();
        Database::table("history")->insert(array("file" => $data["document_key"], "activity" => $activity, "type" => "default"));
        return $documentId;
    }
    
    /**
     * Copy file
     * 
     * @param   string $filename
     * @return  true
     */
    public static function keepcopy($filename) {
        copy(config("app.storage")."files/".$filename, config("app.storage")."copies/".$filename);
        return true;
    }
    
    /**
     * Copy file
     * 
     * @param   string $filename
     * @return  true
     */
    public static function renamecopy($fileName, $newName) {
        rename(config("app.storage")."copies/".$fileName, config("app.storage")."copies/".$newName);
        return true;
    }
    
    /**
     * Delete a folder
     * 
     * @param   string|int $folderId
     * @return  true
     */
    public static function deletefolder($folderId) {
        $foldersToDelete = $filesToDelete = array();
        $user = Auth::user();
        $thisFolder = Database::table("folders")->where("id", $folderId)->first();
        if (Auth::cid() != $thisFolder->company) {
            return false;
        }

        $folders = Database::table("folders")
                         ->where("id", $folderId)
                         ->get();
        foreach ($folders as $folder) {
            $foldersToDelete[] = $folder->id;
            $folderFiles = Database::table("files")->where("folder", $folder->id)->get();
            foreach ($folderFiles as $file) {
                self::deletefile($file->filename, true);
            }
            //self::deletefolder($folder->id);
            Database::table("folders")->where("id", $folder->id)->delete();
        }
        $folderFiles = Database::table("files")->where("folder", $folderId)->get();

        foreach ($folderFiles as $file) {
            self::deletefile($file->filename, true);
        }
        Database::table("folders")->where("id", $folderId)->delete();
        return true;
    }
    
    /**
     * Delete a file
     * 
     * @param   int $fileId
     * @return  true
     */
    public static function deletefile($fileId, $actualFile = false) {
        if (!$actualFile) {
            $user = Auth::user();
            $thisFile = Database::table("files")->where("id", $fileId)->first();
            if (Auth::cid() != $thisFile->company) {
                return false;
            }else if($user->role = "user" AND $thisFile->uploaded_by != $user->id){
            }else{
                File::delete($thisFile->filename, "files");
                File::delete($thisFile->filename, "copies");
                Database::table("files")->where("id", $thisFile->id)->delete();
            }
        }else{
            if ($actualFile == "original") {
                File::delete($fileId, "files");
            }else{
                File::delete($fileId, "files");
                File::delete($fileId, "copies");
            }
        }
    	return true;
    }
    
    /**
     * Record file history
     * 
     * @param   string $document_key
     * @param   string $activity
     * @param   string $type
     * @return  true
     */
    public static function keephistory($document_key, $activity, $type = "default") {
        Database::table("history")->insert(array("file" => $document_key, "activity" => $activity, "type" => $type));
        return true;
    }
    
    /**
     * Save notifications
     * 
     * @param   int $user
     * @param   string $notification
     * @param   string $type
     * @return  true
     */
    public static function notification($user, $notification, $type = "warning") {
        Database::table("notifications")->insert(array("user" => $user, "message" => $notification, "type" => $type));
        return true;
    }

    /**
     * Check file orientation
     * 
     * @param   float $width
     * @param   float $height
     * @return  string
     */
    public static function orientation($width, $height) {
        if ($width > $height) {
            return "L";
        }else{
            return "P";
        }
    }

    
    /**
     * Sign & Edit document
     * 
     * @param   string $document_key
     * @return  array
     */
    public static function sign($document_key, $actions, $docWidth, $signing_key, $guestMode = false) {
        $document = Database::table("files")->where("document_key", $document_key)->first();

        $user = Auth::user();
        if ($user!=null){
            $userName = $user->fname.' '.$user->lname;
            $signature = config("app.storage")."signatures/".$user->signature;
            $document->outputName=($document->is_template=='Yes')?"t_".$document->id:$user->id;
        }
        else{  //Guest Mode Sign  -  is it possible case?
            $userName = "Guest";
            $signature = null;
            $document->outputName="g_".$document->id;
        }
        $document->outputName.="_".Str::random(2).".pdf";

        $actions = json_decode(base64_decode($actions), true);
        PdfBuilder::Build($document,$actions,$docWidth,null,$guestMode,$signature);
        if ($document->filename!='PIGEON_FORGE.pdf')
            Signer::deletefile($document->filename, "original");
        //=====================

        if (!empty($signing_key)) {  //when Sign
            $request = Database::table("requests")->where("signing_key", $signing_key)->first();
            $sender = Database::table("users")->find($request->sender);
            Database::table("requests")->where("signing_key", $signing_key)->update(array("status" => "Signed", "update_time" => date("Y-m-d H-i-s")));
            StudentModel::Update($request->receiver,array('sign_status'=>StudentModel::Signed));
            $notification = '<span class="text-primary">'.escape($userName).'</span> accepted a signing invitation of this <a href="'.url("Document@open").$request->document.'">document</a>.';
            Signer::notification($sender->id, $notification, "accept");
            $documentLink = env("APP_URL")."/document/".$request->document;
//            if ($sender->email!='muellerUnity@gmail.com' && $sender->email!='kimunsim1991417@gmail.com'){
//                Mail::send(
//                    $sender->email, "Signed from ".$request->email,
//                    array(
//                        "title" => "Signing accepted.",
//                        "subtitle" => "Click the link below to view document.",
//                        "buttonText" => "View Document",
//                        "buttonLink" => $documentLink,
//                        "message" => "A student has accepted and signed the signing invitation you had sent. <br>Click the link above to view the document.<br><br>Thank you"
//                    ),
//                    "withbutton"
//                );
//            }
        }

        $actionTakenBy = escape($userName);
        /*
         * Check, whether IP address register is allowed in .env
         * If yes, then capture the user's IP address
         */
        if (env('REGISTER_IP_ADDRESS_IN_HISTORY') == 'Enabled') {
            $actionTakenBy .= ' ['.getUserIpAddr().']';
        }

//        if ($editted) {
//            $activity = '<span class="text-primary">'.$actionTakenBy.'</span> editted the document.';
//            Signer::keephistory($document->document_key, $activity);
//        }
//        if ($signed) {
//            Database::table("files")->where("document_key", $document->document_key)->update(array("status" => "Signed"));
//            $activity = '<span class="text-primary">'.$actionTakenBy.'</span> signed the document.';
//            Signer::keephistory($document->document_key, $activity, "success");
//        }
//        self::renamecopy($document->filename, $outputName);
        return true;
    }
    
    /**
     * Scale position on axis
     * 
     * @param   int $position
     * @return  int
     */
    public static function adjustPositions($position) {
        return round($position - 83);
    }
    
    /**
     * Get Ip Address
     * 
     * @param   int $position
     * @return  int
     */
    public static function ipaddress() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
     
        return $ipaddress;
    }

}