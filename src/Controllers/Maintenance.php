<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use Simcify\File;
use Simcify\Mail;
use Simcify\Models\StudentModel;

class Maintenance{
    public function list() {
        $user=Auth::user();
        if ($user->role =='user')
            $list = Database::table("maintenance_requests")->where('user_id',$user->id)->orderBy('id', false)->get();
        else
            $list = Database::table("maintenance_requests")->company()->orderBy('id', false)->get();
        return view('maintenance', compact('user','list'));
    }

    public function tab($user_id) {
        $user=Auth::user();
        $student= StudentModel::GetUser($user_id);
//        $user->page_title='Take Picture';
        $list = Database::table("maintenance_requests")->where('room_id',$student->room_id)->orderBy('id', false)->get();
        return view('maintenance', compact('user','student','list'));
    }

    public function create() {
        header('Content-type: application/json');
        $user=Auth::user();
        $student_id=input('student_id',0);
        $student=($student_id>0)?StudentModel::GetUser($student_id):$user;
        $ad = array(
            "content" => $_POST["content"],
            "permission" => $_POST["permission"],
            "preferred_time" => input('preferred_time'),
            'user_id'=>$user->id,
            'room_id'=>$student->room_id,
            'requester_name'=>$user->fname.' '.$user->lname,
            'unit'=>$student->unit,
            'company' => Auth::cid()
        );

        if (!empty(input("maintenance_image"))){
            $image = File::upload(
                input("maintenance_image"),
                "maintenance",
                array(
                    "source" => "base64",
                    "extension" => "png"
                )
            );
            if ($image['status'] == "success")
                $ad['image']=$image['info']['name'];
        }

        Database::table("maintenance_requests")->insert($ad);
        Customer::addActionLog("maintenance", "new request", "From the Student ".$ad['requester_name']."  ID:". $user->id,$student->id);
        $subject="New Maintenance Request from ".env("Site_Name");
        Mail::send(
            env("Mail_Bill"), $subject,
            array(
                "message" =>"New Maintenance Request was come from ".$ad['unit']."<br><br> ".$ad['content']
            )
        );
        exit(json_encode(responder("success","Alright", "New Maintenance Request was successfully submitted","reload()")));
    }

    public function updateview() {
        $id=input("id");
        if (Auth::user()->role=='user')
            $comments=Database::table("maintenance_comments")->where('maintenance_id',$id)->where('internal','external')->get();
        else
            $comments=Database::table("maintenance_comments")->where('maintenance_id',$id)->get();

        $data = array(
            "request" => Database::table("maintenance_requests")->find($id),
            "comments" => $comments,
            'status_list'=>['Waiting materials','More info needed','Completed'],
            'user'=>Auth::user()
        );
        return view('extras/update_maintenance', $data);
    }

    public function update() {
        $id=input('id');
        $status=input('status');
        $content=input('content');
        $internal=input('internal')?'internal':'external';
        if (!empty($status)){
            Database::table("maintenance_requests")->where("id" , $id)->update(array('status' => $status));
            if ($status=='Completed'){
                $request=Database::table("maintenance_requests")->find($id);
                $user=StudentModel::GetUser($request->user_id);
                Mail::send(
                    $user->email, "Maintenance request",
                    array(
                        "message" =>'Your maintenance request was completed'
                    )
                );
            }
        }

        if (!empty($content)){
            $user=Auth::user();
            $ad=array('user_id'=>$user->id,
                'maintenance_id'=>$id,
                'author_name'=>$user->fname.' '.$user->lname,
                'content'=>$content,
                'internal'=>$internal
            );

            if (!empty(input("maintenance_image"))){
                $image = File::upload(
                    input("maintenance_image"),
                    "maintenance",
                    array(
                        "source" => "base64",
                        "extension" => "png"
                    )
                );
                if ($image['status'] == "success")
                    $ad['image']=$image['info']['name'];
            }

            Database::table("maintenance_comments")->insert($ad);
        }

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Alright!", "The maintenance request was successfully updated","reload()")));
    }
}
