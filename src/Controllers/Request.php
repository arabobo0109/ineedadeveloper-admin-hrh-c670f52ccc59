<?php
namespace Simcify\Controllers;

use Simcify\Str;
use Simcify\File;
use Simcify\Mail;
use Simcify\Auth;
use Simcify\Signer;
use Simcify\Database;

class Request{

    /**
     * Get requests view
     *
     * @return \Pecee\Http\Response
     */
    public function get() {
        $user = Auth::user();
        if (!isset($_GET['view'])) {
            if ($user->role == "user") {
                $requestsData = Database::table("requests")->where("sender", $user->id)->orderBy("id", false)->get();
            }else{
                $requestsData = Database::table("requests")->company()->orderBy("id", false)->get();
            }
        } else {
            $requestsData = Database::table("requests")->where("receiver", $user->id)->orderBy("id", false)->get();
        }
        $requests = array();
        foreach ($requestsData as $request) {
        	if (!empty($request->receiver)) {
        		$receiver = Database::table("users")->find($request->receiver);
        		if (!empty($receiver)) {
        			$receiverInfo = $receiver;
        		}
        	}else{
    			$receiver = Database::table("users")->where("email" , $request->email)->first();
        		if (!empty($receiver)) {
        			$receiverInfo = $receiver;
        		}else{
        			$receiverInfo = $request->email;
        		}
    		}
        	$requests[] = array(
                                                "data" => $request,
                                                "file" => Database::table("files")->where("document_key" , $request->document)->first(),
                                                "sender" => Database::table("users")->find($request->sender),
                                                "receiver" => $receiverInfo
                                            );
        }
        
        return view('requests', compact("user", "requests"));
    }


    /**
     * Delete signing request
     * 
     * @return Json
     */
    public function delete() {
    	header('Content-type: application/json');
    	$requestId = input("requestid");
    	Database::table("requests")->where("id", $requestId)->delete();
    	exit(json_encode(responder("success", "Deleted!", "Request successfully deleted.","reload()")));
    }

    /**
     * Cancel signing request
     * 
     * @return Json
     */
    public function cancel() {
    	header('Content-type: application/json');
    	$requestId = input("requestid");
    	Database::table("requests")->where("id", $requestId)->update(array("status" => "Cancelled", "update_time" => date("Y-m-d H-i-s")));
    	exit(json_encode(responder("success", "Cancelled!", "Request successfully cancelled.","reload()")));
    }

    /**
     * Send a signing request reminder
     * 
     * @return Json
     */
    public function remind() {
        header('Content-type: application/json');
        $requestId = input("requestid");
        $user = Auth::user();
        $request = Database::table("requests")->where("id", $requestId)->first();
        $signingLink = env("APP_URL")."/document/".$request->document."?signingKey=".$request->signing_key;
        Mail::send(
            $request->email, "Signing invitation reminder from ".$user->fname." ".$user->lname,
            array(
                "title" => "Signing invitation reminder.",
                "subtitle" => "Click the link below to respond to the invite.",
                "buttonText" => "Sign Now",
                "buttonLink" => $signingLink,
                "message" => "You have been invited to sign a document by ".$user->fname." ".$user->lname.". Click the link above to respond to the invite.<br><strong>Message:</strong> ".input("message")."<br><br>Thank you<br>".env("APP_NAME")." Team"
            ),
            "withbutton"
        );
        $actionTakenBy = escape($user->fname.' '.$user->lname);
        /*
         * Check, whether IP address register is allowed in .env
         * If yes, then capture the user's IP address
         */
        if (env('REGISTER_IP_ADDRESS_IN_HISTORY') == 'Enabled') {
            $actionTakenBy .= ' ['.getUserIpAddr().']';
        }
        $activity = '<span class="text-primary">'.$actionTakenBy.'</span> sent a signing reminder to <span class="text-primary">'.escape($request->email).'</span>.';
        Signer::keephistory($request->document, $activity, "default");
        exit(json_encode(responder("success", "Sent!", "Reminder successfully send.","reload()")));
    }

    /**
     * Decline a signing request 
     *
     * @return Json
     */
    public function decline() {
    	header('Content-type: application/json');
    	$requestId = input("requestid");
    	$user = Auth::user();
        Database::table("requests")->where("id", $requestId)->update(array("status" => "Declined", "update_time" => date("Y-m-d H-i-s")));
        $request = Database::table("requests")->where("id", $requestId)->first();
        $sender = Database::table("users")->find($request->sender);
        $documentLink = env("APP_URL")."/document/".$request->document;
        Mail::send(
            $sender->email, "Signing invitation declined by ".$user->fname." ".$user->lname,
            array(
                "title" => "Signing invitation declined.",
                "subtitle" => "Click the link below to view document.",
                "buttonText" => "View Document",
                "buttonLink" => $documentLink,
                "message" => $user->fname." ".$user->lname." has declined the signing invitation you had sent. Click the link above to view the document.<br><br>Thank you<br>".env("APP_NAME")." Team"
            ),
            "withbutton"
        );
        $actionTakenBy = escape($user->fname.' '.$user->lname);
        /*
         * Check, whether IP address register is allowed in .env
         * If yes, then capture the user's IP address
         */
        if (env('REGISTER_IP_ADDRESS_IN_HISTORY') == 'Enabled') {
            $actionTakenBy .= ' ['.getUserIpAddr().']';
        }
        $activity = '<span class="text-primary">'.$actionTakenBy.'</span> declined a signing invitation of this document.';
        Signer::keephistory($request->document, $activity, "default");
        $notification = '<span class="text-primary">'.escape($user->fname.' '.$user->lname).'</span> declined a signing invitation of this <a href="'.url("Document@open").$request->document.'">document</a>.';
        Signer::notification($sender->id, $notification, "decline");
    	exit(json_encode(responder("success", "Declined!", "Request declined and sender notified.","reload()")));
    }

}
