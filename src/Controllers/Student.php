<?php

namespace Simcify\Controllers;

use Exception;
use Simcify\Models\BalanceModel;
use Simcify\Str;
use Simcify\File;
use Simcify\Mail;
use Simcify\Auth;
use Simcify\Database;
use Simcify\Signer;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Simcify\Models\StudentModel;
use PDO;
use function Simcify\print_r2;

class Student
{
    public function updateDashboardUser() {
        $ud=array();
        foreach (input()->post as $field) {

            if ($field->index == "csrf-token" || $field->index == "customerid" || ($field->index == "birthday" && empty($field->value))) {
                continue;
            }
            $ud[$field->index] = escape($field->value);
        }
        $res=StudentModel::Update(input("customerid"),$ud);
        header('Content-type: application/json');
        if ($res)
            exit(json_encode(responder("success", "Alright", "Student data successfully updated","reload()")));
        else
            exit(json_encode(responder("error", "The information can't be updated.", Database::$error,"reload()")));
    }

    public function updateRoommate() {
        header('Content-type: application/json');

        $roommate_message="";
        $ids=$_POST['id'];
        for ($i=0;$i<3;$i++){
            $dd=array('user_id'=>input('user_id'),
                'fname'=>$_POST['fname'][$i],
                'lname'=>$_POST['lname'][$i],
                'business_name'=>$_POST['business_name'][$i],
                'lease_start'=>$_POST['lease_start'][$i]
                );
            if (!empty($dd['fname'])){
                if (input('from')=='docsign'){  // send email when create account
                    $roommate_message.="<br>".$dd['fname'].' '.$dd['lname'];
                }
                if (empty($dd['business_name'])){
                    exit(json_encode(responder("error", "Employer name is needed", "Please input ".$dd['fname']."'s Employer name")));
                }
                else if (empty($dd['lease_start'])){
                    exit(json_encode(responder("error", " Arrival Date is needed", "Please input ".$dd['fname']."'s Arrival Date")));
                }
            }
            if (is_numeric($ids[$i])){
                Database::table("roommates")->where('id',$ids[$i])->update($dd);
            }
            elseif (!empty($dd['lease_start'])){
                Database::table("roommates")->insert($dd);
            }
        }
        if (input('from')=='docsign') {  // from create account
            if ($roommate_message != "") {
                $user = StudentModel::GetUser(input('user_id'));
                $message = "from Student(" . $user->email . ")  <strong>" . $user->fname . " " . $user->lname . "</strong><br>" . $roommate_message."<br><br> Employer:".StudentModel::getEmployerName($user)."<br><br> ***Please remember that all roommate requests are up to the discretion of management and will be accommodated to the best of our ability based upon occupancy.***";
                Mail::send(env("RoommateRequestMail"), "Roommate was requested",
                    array(
                        "message" => $message
                    ),
                    "basic", null, [],
                    $user->email,
                    $user->fname . " " . $user->lname
                );
            }
            $redirectLink =url('Student@profilePicture').'passport';
        }
        else {
            if (Auth::user()->role == 'user')
                $redirectLink = url('Dashboard@get');
            else
                $redirectLink = url('Customer@profile') . input('user_id');
        }

        exit(json_encode(responder("success", "Alright", "Roommates were successfully updated","redirect('".$redirectLink."', true);")));
    }

    public function viewRoommate($user_id) {
        $user = Auth::user();

        $student= ($user_id==0)?$user:StudentModel::GetUser($user_id);
        $student->employer_name=StudentModel::getEmployerName($student);
        $data = array(
            "student" => $student,
            "roommates" => Database::table("roommates")->where('user_id',$student->id)->get()
        );
        if ($user_id>0){  // not from create account (After Docsign)
            $data['user']=$user;
        }
        return view('customer/roommate_requested', $data);
    }

    public function profilePicture($mode) {  // from create student (after passport upload)
        $student= Auth::user();
        if (env("SITE_Portal") && $mode=='passport'){
            $passports = Database::table('passports')->where("user_id", $student->id)->first();
            if (empty($passports))
                $passports=true;
            return view('camera/upload_image', compact("student",  "passports" ));
        }
        else
            return view('camera/upload_image', compact("student"));
    }

    public function takePicture($user_id) {
        $student= StudentModel::GetUser($user_id);
        $user = Auth::user();
        $user->page_title='Take Picture';
        return view('camera/upload_image', compact("user",  "student" ));
    }

    public function takePictureID($user_id) {
        $student= StudentModel::GetUser($user_id);
        $user = Auth::user();
        $user->page_title='VISA/ID';
        $passports = Database::table('passports')->where("user_id", $user_id)->first();
        if (empty($passports))
            $passports=true;
        return view('camera/upload_image', compact("user",  "student", "passports" ));
    }

    public function uploadPicture() {
        if(isset($_FILES['avatar'])) {
            $errors = array();
            $file_tmp = $_FILES['avatar']['tmp_name'];
            $file_ext = strtolower(end(explode('.', $_FILES['avatar']['name'])));

            $extensions = array("jpeg", "jpg", "png");
            if (in_array($file_ext, $extensions) === false) {
                $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
            }

            $directory = getcwd() . '/uploads/';

            $user=Database::table('users')->find($_POST["user_id"]);

            if (isset($_POST["document_type"])){ //VISA/ID upload
                $dd = array(
                    "type" => $_POST["document_type"]
                );
                $filename=$dd['type'].'_'.$user->id.'_';
                if($_POST["front_back"] == "front"){
                    $filename .= "1_".Str::random(4)."." . $file_ext;
                    $dd['path0'] = $filename;
                }
                else{
                    $filename .= "2_".Str::random(4)."." . $file_ext;
                    $dd['path1'] = $filename;
                }

                try {
                    if (empty($errors) == true) {
                        move_uploaded_file($file_tmp, $directory.'passport/'. $filename);
                        $passports = Database::table('passports')->where("user_id", $user->id)->first();
                        if(empty($passports)){
                            $dd['user_id'] = $user->id;
                            Database::table('passports')->insert($dd);
                        }
                        else{
                            Database::table('passports')->where("user_id", $user->id)->update($dd);
                        }
                        echo "Success ".$filename;
                    }
                    else {
                        print_r($errors);
                    }
                } catch (Exception $e) {
                    print_r($e->getMessage());
                }
            }
            else{  // upload Profile Image
                $filename = $user->id."_".Str::random(4).".jpg";
                try {
                    if (empty($errors) == true) {
                        move_uploaded_file($file_tmp, $directory.'avatar/' . $filename);  //it will be overwritten.
                        StudentModel::Update($user->id,array("avatar" => $filename));
                        echo "Success ".$filename;
                    } else {
                        print_r($errors);
                    }
                } catch (Exception $e) {
                    print_r($e->getMessage());
                }
            }
        }
    }

    public function saveNote(){
        $user=Auth::user();
        $note_id=input('note_id',0);

        if ($note_id>0){
            $ud=['content'=>addslashes(input('edit_note_content'))];
            Database::table('notes')->where('id',$note_id)->update($ud);
        }
        else{ //add note
            if (input('internal_note',null)!=null){
                StudentModel::AddNote($user,input("user_id"),'internal',addslashes(input('internal_note')));
                if (input('email_support','')=='checked'){
                    $subject="Note from ".$user->fname." in ".env('Site_Name');
                    $student=StudentModel::GetUser(input("user_id"));
                    $mail_content="Student ID:".$student->id;
                    $mail_content.="<br>Student Email:".$student->email;
                    $mail_content.="<br>Student Name:".$student->fname." ".$student->lname;
                    $mail_content.="<br>Room:".$student->unit;
                    $mail_content.="<br><br> Note Content:<br>".addslashes(input('internal_note'));
                    Mail::send(
                        env("Mail_Bill"), $subject,
                        array(
                            "message" =>$mail_content
                        ),
                        "basic", null, [],
                        $user->email,
                        $user->fname . " " . $user->lname
                    );

                    Mail::send(
                        env("DrawerReportMail3"), $subject,
                        array(
                            "message" =>$mail_content
                        ),
                        "basic", null, [],
                        $user->email,
                        $user->fname . " " . $user->lname
                    );
                }
            }
            if (input('extra_note',null)!=null){
                StudentModel::AddNote($user,input("user_id"),'external',addslashes(input('extra_note')));
            }
        }

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Note.","Note was saved successfully.","reload()")));
    }

    public function check_names(){
        $user = Database::table('users')->where('fname',$_POST["fname"])->where('lname',$_POST["lname"])->first();
        header('Content-Type: application/json');
        return json_encode($user);
    }

    public function check_email(){
        $email=$_POST["email"];
        $user = Database::Sql("SELECT id, email, fname, lname, country, status,flag FROM users WHERE email='".$email."'"." or email = CONCAT('T', id, '_', '".$email."')")->getFirstObj();
        header('Content-Type: application/json');
        return json_encode($user);
    }
}
