<?php
namespace Simcify\Controllers;

use Simcify\File;
use Simcify\Mail;
use Simcify\Auth;
use Simcify\Database;
use Simcify\Models\StudentModel;

class Team{

    /**
     * Get team view
     * 
     * @return \Pecee\Http\Response
     */
    public function get() {
        $user = Auth::user();
        if ($user->role != "superadmin" && $user->role != "admin") {
            return view('errors/404');   
        }
        $staffs=Database::table("users")->where("role", "staff")->company()->get();
        $admins=Database::table("users")->where("role", "admin")->company()->get();
        $staffs=array_merge($admins,$staffs);
        foreach ($staffs as $staff){
            $staff->terminal=Database::table("terminals")->where("device_id", $staff->season)->first()->nickname;
        }
	   $data = array(
			"user" => Auth::user(),
			"team" => $staffs
		);
        return view('team', $data);
    }

    /**
     * Create team member account
     * 
     * @return Json
     */
    public function create() {
        header('Content-type: application/json');
        $password = rand(111111, 999999);

    	if (!isset($_POST['permissions'])) {
    		$_POST['permissions'] = array("upload");
    	}else{
    		array_push($_POST['permissions'], "upload");
    	}
        $signup = Auth::signup(
            array(
                "fname" => escape(input('fname')),
                "lname" => escape(input('lname')),
                "phone" => escape(input('phone')),
                "email" => escape(input('email')),
                "role" => "staff",
                "company" => Auth::cid(),
                "password" => Auth::password($password)
            ), 
            array(
                "uniqueEmail" => input('email')
            )
        );
        if ($signup["status"] == "success") {
            Mail::send(
                input('email'),
                "Welcome to ".env("Site_Name")."!",
                array(
                    "title" => "Welcome to ".env("Site_Name")."!",
                    "subtitle" => "A new account has been created for you at ".env("Site_Name").".",
                    "buttonText" => "Login Now",
                    "buttonLink" => env("APP_URL"),
                    "message" => "These are your login Credentials:<br><br>Email:<strong>".input('email')."</strong><br>Password:<strong>".$password."</strong><br><br>Cheers!<br>".env("APP_NAME")." Team."
                ),
                "withbutton"
            );
            exit(json_encode(responder("success", "Member Added", "Staff account successfully created","reload()")));
        }else{
            exit(json_encode($signup));
        }
    }

    /**
     * Delete team member account
     * 
     * @return Json
     */
    public function delete() {
        $account = Database::table("users")->where("id", input("memberid"))->first();
        if (!empty($account->avatar)) {
            File::delete($account->avatar, "avatar");
        }
        Database::table("users")->where("id", input("memberid"))->delete();
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Member Deleted!", "Team member successfully deleted.","reload()")));
    }

    /**
     * Team member update view
     * 
     * @return Json
     */
    public function updateview() {
        $data = array(
                "member" => Database::table("users")->where("id", input("memberid"))->first(),
                'terminals'=>Database::table("terminals")->company()->get()
            );
        return view('extras/updatemember', $data);
    }

    /**
     * Update team member account
     * 
     * @return Json
     */
    public function update() {

        foreach (input()->post as $field) {
        	if (!isset($field->index)) {
        		continue;
        	}
            if ($field->index == "csrf-token" || $field->index == "memberid") {
                continue;
            }
            StudentModel::Update(input("memberid"),array($field->index => escape($field->value)));
        }
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Alright", "Member account successfully updated","reload()")));
    }

    public function changeStatus() {
        $user = Database::table("users")->find(input("user_id"));
        if ($user->action=='Active')
            $newStatus=StudentModel::Suspended;
        else
            $newStatus=StudentModel::Active;
        StudentModel::Update($user->id,array('action'=>$newStatus));
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Status is ".$newStatus, "The member was successfully changed.","reload()")));
    }
}
