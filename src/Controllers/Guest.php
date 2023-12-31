<?php
namespace Simcify\Controllers;

use Simcify\Str;
use Simcify\File;
use Simcify\Mail;
use Simcify\Auth;
use Simcify\Signer;
use Simcify\Database;

class Guest {
    /**
     * Get documents view
     * 
     * @return \Pecee\Http\Response
     */
    public function open($signingKey) {
        if (isset($_COOKIE['guest'])) {
          cookie("guest", '', -7);
        }
        $request = Database::table("requests")->where("signing_key", $signingKey)->first();
        if (empty($request))
            return view('errors/404');
        $user=Database::table("users")->find($request->receiver);

        if (isset($_GET['cmd'])) {  //create account
            return view('create_account',
                array('signingKey'=>$signingKey,
                    "user_id" => $user->id,
                    "email"=>$user->email,
                    "pin"=>$user->pin,
                    "lease_end"=>$user->lease_end));
        }
        $document = Database::table("files")->where("document_key", $request->document)->first();
        $requestPositions = json_decode($request->positions, true);
        $requestWidth = $requestPositions[0];
        $requestPositions = json_encode($requestPositions, true);
        if (empty($requestWidth)) { $requestWidth = 0; }
        $request->user_name=$user->fname.' '.$user->lname;
        $request->user_id=$user->id;

        $lauchLabel = "Sign & Edit";
        return view('open', compact("document","lauchLabel","request","requestWidth","requestPositions"));
    }

    /**
     * Download a file
     * 
     * @return \Pecee\Http\Response
     */
    public function download($docId) {
        $document = Database::table("files")->where("id", $docId)->first();
        $file = config("app.storage")."files/".$document->filename;
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$document->name.'.'.$document->extension.'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        flush();
        readfile($file);
        exit();
    }

    /**
     * Sign & Edit Document
     * 
     * @return Json
     */
    public function sign() {
        header('Content-type: application/json');
        $sign = Signer::sign(input("document_key"), input("actions"), input("docWidth"), input("signing_key"), true);
        if ($sign) {
            exit(json_encode(responder("success", "Alright!", "Docusign successfully submitted.","redirect('".url("Student@viewRoommate")."0',true)")));
        }else{
            exit(json_encode(responder("error", "Oops!", "Something went wrong, please try again.")));
        }
        
    }

    /**
     * Decline a signing request 
     * 
     * @return Json
     */
    public function decline() {
      header('Content-type: application/json');
      $requestId = input("requestid");
        Database::table("requests")->where("id", $requestId)->update(array("status" => "Declined"));
        $request = Database::table("requests")->where("id", $requestId)->first();
        $sender = Database::table("users")->find($request->sender);
        $documentLink = env("APP_URL")."/document/".$request->document;
        Mail::send(
            $sender->email, "Signing invitation declined by ".$request->email,
            array(
                "title" => "Signing invitation declined.",
                "subtitle" => "Click the link below to view document.",
                "buttonText" => "View Document",
                "buttonLink" => $documentLink,
                "message" => $request->email." has declined the signing invitation you had sent. Click the link above to view the document.<br><br>Thank you<br>".env("APP_NAME")." Team"
            ),
            "withbutton"
        );
        $actionTakenBy = escape($request->email);
        /*
         * Check, whether IP address register is allowed in .env
         * If yes, then capture the user's IP address
         */
        if (env('REGISTER_IP_ADDRESS_IN_HISTORY') == 'Enabled') {
            $actionTakenBy .= ' ['.getUserIpAddr().']';
        }
        $activity = '<span class="text-primary">'.$actionTakenBy.'</span> declined a signing invitation of this document.';
        Signer::keephistory($request->document, $activity, "default");
        $notification = '<span class="text-primary">'.escape($request->email).'</span> declined a signing invitation of this <a href="'.url("Document@open").$request->document.'">document</a>.';
        Signer::notification($sender->id, $notification, "decline");
      exit(json_encode(responder("success", "Declined!", "Request declined and sender notified.","reload()")));
    }

    /**
     * Decline a signing request 
     * 
     * @return Json
     */
    public function mailopen() {
        $signingKey = $_GET['signingKey'];
        $request = Database::table("requests")->where("signing_key", $signingKey)->first();
        $actionTakenBy = escape($request->email);
        /*
         * Check, whether IP address register is allowed in .env
         * If yes, then capture the user's IP address
         */
        if (env('REGISTER_IP_ADDRESS_IN_HISTORY') == 'Enabled') {
            $actionTakenBy .= ' ['.getUserIpAddr().']';
        }
        $activity = '<span class="text-primary">'.$actionTakenBy.'</span> opened signing invitation email of this document.';
        Signer::keephistory($request->document, $activity, "default");
        $notification = '<span class="text-primary">'.escape($request->email).'</span> has opened the email signing requests sent for this <a href="'.url("Document@open").$request->document.'">document</a>.';
        Signer::notification($request->sender, $notification, "accept");
        exit();
    }

    /**
     * Email open
     * 
     * @return NULL
     */
    public function mailopent($signingKey) {
        $request = Database::table("requests")->where("signing_key", $signingKey)->first();
        $activity = '<span class="text-primary">'.escape($request->email).'</span> opened signing invitation email of this document.';
        Signer::keephistory($request->document, $activity, "default");
        $notification = '<span class="text-primary">'.escape($request->email).'</span> has opened the email signing requests sent for this <a href="'.url("Document@open").$request->document.'">document</a>.';
        Signer::notification($sender->id, $notification, "accept");
        exit();
    }
}
