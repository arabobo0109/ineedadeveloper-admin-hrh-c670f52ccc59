<?php

include_once 'vendor/autoload.php';

use Simcify\Application;
use Simcify\Database;
use Simcify\Mail;
use Simcify\Auth;

define("EmailLog",$_SERVER['DOCUMENT_ROOT']."/reminder.log");

$app = new Application();

$today = date("Y-m-d");
$current_time = date("Y-m-d H:i:s");

send_checkin_reminder();
//send_payment_reminder();

/****    Checkin Reminder Function   ****/
function send_checkin_reminder(){
    global $today, $current_time;
    $checkin_reminders = Database::table("reminders")->get();
    if ( count($checkin_reminders) > 0 ) {
        foreach ($checkin_reminders as $checkin_reminder) {
            $requestDate = date('Y-m-d', strtotime($today. ' - '.$checkin_reminder->days.' days'));
            $users = Database::table("users")->company()->where("lease_start",">=", $requestDate)->where("lease_start","<=", $requestDate." 23:59:59.999")->get();
            if ( count($users) > 0 ) {
                foreach ($users as $user) {
                    $reminder_mail = Database::table("reminder_mail")->where("reminder_type", "checkin")->where("reminder_id", $checkin_reminder->id)->where("student_id",$user->id)->get();
                    if ( count($reminder_mail) > 0 )
                        continue;

                    $token = Str::random(32);
                    $data = array('token' => $token);
                    Database::table('users')->where("email", $user->email)->update($data);
                    $link = env("APP_URL")."/checkin?token=".$token;

                    Mail::send(
                        $user->email, $checkin_reminder->subject,
                        array(
                            "title" => "Check-in reminder",
                            "subtitle" => "Click the link below to check-in.",
                            "buttonText" => "Check-in now",
                            "buttonLink" => $link,
                            "message" => $checkin_reminder->message
                        ),
                        "withbutton"
                    );
                    $insert_data = array(
                        "reminder_type" => "checkin",
                        "reminder_id" => $checkin_reminder->id,
                        "student_id" => $user->id
                    );
                    Database::table("reminder_mail")->insert($insert_data);
                    $activity = 'Successfully automated check-in email sent to '.$user->email;

                    error_log(date("Y-m-d H:i:s")."\n".$activity."\n", 3,EmailLog);
                }
            }else{
                continue;
            }
        }
    }
}


/****   Payment Reminder  Function  ****/
function send_payment_reminder(){
    global $today, $current_time;
    $payment_reminders = Database::table("payment_reminders")->company()->get();
    if ( count($payment_reminders) > 0 ) {
        $index =  0;
        foreach ($payment_reminders as $payment_reminder) {
            $index++;
            if($index == 1)
                $requestDate = date('Y-m-d H:i:s', strtotime($current_time. ' - '.$payment_reminder->hours.' mins'));
            else
                $requestDate = date('Y-m-d H:i:s', strtotime($current_time. ' - '.$payment_reminder->hours.' hours'));

            $users = Database::table("users")->company()->where("created_at","<=", $requestDate)->where("role","user")->get();
            if ( count($users) > 0 ) {
                foreach ($users as $user) {
                    $reminder_mail = Database::table("reminder_mail")->where("reminder_type", "payment")->where("reminder_id", $payment_reminder->id)->where("student_id",$user->id)->get();
                    if ( count($reminder_mail) > 0 )
                        continue;
                    $invoice = Database::table("invoices")->where("student_id", $user->id)->get();
                    if ( count($invoice) > 0 )
                        continue;

                    $link = env("APP_URL");

                    Mail::send(
                        $user->email, $payment_reminder->subject,
                        array(
                            "title" => "Payment reminder",
                            "subtitle" => "Click the link below to payment.",
                            "buttonText" => "Payment now",
                            "buttonLink" => $link,
                            "message" => $payment_reminder->message
                        ),
                        "withbutton"
                    );

                    $insert_data = array(
                        "reminder_type" => "payment",
                        "reminder_id" => $payment_reminder->id,
                        "student_id" => $user->id
                    );
                    Database::table("reminder_mail")->insert($insert_data);
                    $activity = 'Successfully automated payment email sent to '.$user->email;

                    error_log(date("Y-m-d H:i:s")."\n".$activity."\n", 3,EmailLog);
                }
            }else{
                continue;
            }
        }
    }
}

