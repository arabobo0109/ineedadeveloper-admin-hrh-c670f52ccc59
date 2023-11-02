<?php
namespace Simcify\Controllers;

use Simcify\Date;
use Simcify\File;
use Simcify\Auth;
use Simcify\Database;
use DotEnvWriter\DotEnvWriter;
use Simcify\Models\CompanyModel;
use Simcify\Models\PaymentModel;
use Simcify\Models\ReportModel;
use Simcify\Models\StudentModel;

class Settings{

    /**
     * Get settings view
     * 
     * @return \Pecee\Http\Response
     */
    public function get() {
        $user = Auth::user();
        $company = CompanyModel::GetCompany();
        if ($user->role == "user") {
        	$room =  Database::table("rooms")->where("id", Auth::user()->room_id)->first();
        	$bed  = Database::table("beds")->where("id", Auth::user()->bed_id)->first();
        	return view('settings', compact("user","company", "room", "bed"));
        }

        $reminders = Database::table("reminders")->company()->get();
        $payment_reminders = Database::table("payment_reminders")->company()->get();
        $monday_emails = Database::table("monday_emails")->company()->get();

        return view('settings', compact("user","company","reminders", "payment_reminders", "monday_emails"));
    }

    public function changeOrderBySpecialFee( $bed_id, $special_weekly, $special_daily,$previousWeekly){
        ReportModel::SendError("Special Fee was updated bed_id:".$bed_id." currently weekly:".$special_weekly." previous:".$previousWeekly);
    }

    /**
     * Update profile on settings page
     * 
     * @return Json
     */
    public function updateprofile() {
        header('Content-type: application/json;');
    	$account = Database::table('users')->where("email" , input("email"))->first();
    	if (!empty($account) && $account->id != Auth::user()->id) {
			exit(json_encode(responder("error", "Oops", input("email"). " already exists.")));
    	}
    	foreach (input()->post as $field) {
    		if ($field->index == "avatar") {
    			if (!empty($field->value)) {
    				$avatar = File::upload(
					    $field->value, 
					    "avatar",
					    array(
					        "source" => "base64",
					        "extension" => "png"
					    )
					);

    				if ($avatar['status'] == "success") {
	    				if (!empty(Auth::user()->avatar)) {
	    					File::delete(Auth::user()->avatar, "avatar");
	    				}
    					Database::table('users')->where("id" , Auth::user()->id)->update(array("avatar" => $avatar['info']['name']));
    				}
    			}
    			continue;
    		}
            
    		if ($field->index == "csrf-token") {
    			continue;
    		}

    		Database::table('users')->where("id" , Auth::user()->id)->update(array($field->index => $field->value));
    	}
		exit(json_encode(responder("success", "Alright", "Profile successfully updated","reload()")));
    }

    /**
     * Update company on settings page
     * 
     * @return Json
     */
    public function updatecompany() {
        if(input("security") == 0)
        {
            header('Content-type: application/json');
            exit(json_encode(responder("error", "Zero Value", "Please input non zero value for Security Deposit")));
        }
    	foreach (input()->post as $field) {
    		if ($field->index == "csrf-token") {
    			continue;
    		}

    		Database::table("companies")->where("id" , Auth::cid())->update(array($field->index => escape($field->value)));
    	}

        $r = Payment::UpdatePlanNMI(env("NMI_PLAN_DEFAULT_ID"), input("weekly"));

        // Action Log
        Customer::addActionLog("Setting", "Update Fees", "Updated to $".input("weekly"));

        header('Content-type: application/json');
		exit(json_encode(responder("success", "Alright", "Company info successfully updated")));
    }

    /**
     * Update reminders on settings page
     * 
     * @return Json
     */
    public function updatereminders() {
        $user = Auth::user();
    	if (empty(input("reminders"))) {
    		Database::table("companies")->where("id" , Auth::cid())->update(array("reminders" => "Off"));
    	}else{
    		Database::table("companies")->where("id" , Auth::cid())->update(array("reminders" => "On"));
    	}
    	Database::table("reminders")->company()->delete();
    	foreach( input("subject") as $index => $subject ) {
    		$reminder = array(
    				"company" => Auth::cid(),
    				"days" => input("days")[$index],
    				"subject" => escape(input("subject")[$index]),
    				"message" => escape(input("message")[$index])
    			);
    		Database::table("reminders")->insert($reminder);
    	}
        header('Content-type: application/json');
		exit(json_encode(responder("success", "Alright", "Check-in Reminders successfully updated")));
    }
    
    public function updatepaymentreminders() {
        $user = Auth::user();
    	if (empty(input("reminders"))) {
    		Database::table("companies")->where("id" , Auth::cid())->update(array("reminders" => "Off"));
    	}else{
    		Database::table("companies")->where("id" , Auth::cid())->update(array("reminders" => "On"));
    	}
    	Database::table("payment_reminders")->company()->delete();
    	foreach( input("subject") as $index => $subject ) {
    		$reminder = array(
    				"company" => Auth::cid(),
    				"hours" => input("hours")[$index],
    				"subject" => escape(input("subject")[$index]),
    				"message" => escape(input("message")[$index])
    			);
    		Database::table("payment_reminders")->insert($reminder);
    	}
        header('Content-type: application/json');
		exit(json_encode(responder("success", "Alright", "Payment Reminders successfully updated")));
    }

    // update monday email list
    public function updateMondayEmails() {
        Database::table("monday_emails")->company()->delete();
        foreach( input("email") as $index => $subject ) {
            $reminder = array(
                "email" => escape(input("email")[$index]),
                "name" => escape(input("name")[$index]),
                'company' => Auth::cid()
            );
            Database::table("monday_emails")->insert($reminder);
        }
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Alright", "Monday report emails were successfully updated")));
    }

    /**
     * Update password on settings page
     * 
     * @return Json
     */
    public function updatepassword() {
	    header('Content-type: application/json');
    	if(hash_compare(Auth::user()->password, Auth::password(input("current")))){
    		Database::table('users')->where("id" , Auth::user()->id)->update(array("password" => Auth::password(input("password"))));
			exit(json_encode(responder("success", "Alright", "Password successfully updated", "reload()")));
    	}else{
    		exit(json_encode(responder("error", "Oops", "You have entered an incorrect password.")));
    	}
    }

    public function synchronizeTimezone()
    {
        header('Content-type: application/json');
        Database::table('users')->synchronizeTimezone();
        exit(json_encode(responder("success", "Alright", "System time synchronized successfully", "reload()")));
    }

    public static function actionLog(){

        return view('action_log',ActionModel::getAllActions());
        
    }
}
