<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use Simcify\Mail;
use Simcify\Models\BalanceModel;
use Simcify\Models\EmailModel;
use Simcify\Models\OrderModel;
use Simcify\Models\StudentModel;

class EmailTemplate extends Admin {
    public function get() {
        if (isset($_GET['tab'])) {
            $data = array(
                "user" => Auth::user(),
                "email_templates" => Database::table("custom_mail")->company()->get()
            );
        }
        else{
            $data = array(
                "user" => Auth::user(),
                "email_templates" => Database::table("email_templates")->company()->get()
            );
        }
        return view('email_template', $data);
    }


    public function create() {
        header('Content-type: application/json');
        $template_data = array(
            "name" => $_POST["template_name"],
            "title" => $_POST["title"],
            "content" => urldecode($_POST["content"]),
            'company' => Auth::cid()
        );
        Database::table("email_templates")->insert($template_data);

        // Action Log
        Customer::addActionLog("Email Template", "Create Template", "Created Template : ". input("template_name"));

        exit(json_encode(responder("success","Alright", "Email Template successfully created","reload()")));
    }

    public function delete() {
        Database::table(input("table"))->where("id", input("templateid"))->delete();
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Email Deleted!", "Email successfully deleted.","reload()")));
    }

    public function updateview() {
        $data = array(
                "email_templates" => Database::table(input("table"))->where("id", input("templateid"))->first()
            );
        return view('extras/update_email_template', $data);
    }

    public function update() {
        header('Content-type: application/json');
        $template_data = array(
            "name" => $_POST["template_name"],
            "title" => $_POST["title"],
            "content" => urldecode($_POST["content"])
        );
        Database::table("email_templates")->where("id" , input("templateid"))->update($template_data);

        // Action Log
        Customer::addActionLog("Email Template", "Update Template", "Updated Email Template : ". input("template_name"));


        exit(json_encode(responder("success", "Alright!", "Email Template was successfully updated","reload()")));
    }

// Send Email to all students or one student
    public function sendEmail(){
        header('Content-type: application/json');

        $mail_data = array(
            "sender_id" => Auth::id(),
            "check_sent" => EmailModel::Pending,
            'company' => Auth::cid()
        );

        $email_switch = input("email_switch");
        if($email_switch){
            $mail_title = input("mail_title");
            $mail_content = urldecode($_POST["content"]);
            $mail_data['title']=$mail_title;
            $mail_data['content']=$mail_content;
        }
        else{
            $mail_template = Database::table("email_templates")->find(input("email_template_id"));
            $mail_data['template_id']=$mail_template->id;
            $mail_title = $mail_template->title;
            $mail_content = $mail_template->content;
        }

        if (input("student_id",'all')=='all'){ //all
            $all_students = Database::table("users")->company()->where("role","user")->where("status", input("status"))->get();
            foreach($all_students as $each_student){
                $mail_data['receiver_email'] = str_replace('T'.$each_student->id.'_','',$each_student->email);
                Database::table("custom_mail")->insert($mail_data);
            }

            exit(json_encode(responder("success", "Send Email!", "Emails will be sent to all students.","reload()")));
        }
        else{  //send to one student
            $student = Database::table("users")->find(input("student_id"));
            $mail_data['receiver_email']=str_replace('T'.$student->id.'_','',$student->email);
            $send = Mail::send(
                $mail_data['receiver_email'], $mail_title,
                array(
                    "message" => $mail_content
                )
            );
            $mail_data['check_sent']=$send?EmailModel::Sent:EmailModel::Error;
            Database::table("custom_mail")->insert($mail_data);

            exit(json_encode(responder("success", "Send Email!", "An Email sent to " . $student->email,"reload()")));
        }
    }
}
