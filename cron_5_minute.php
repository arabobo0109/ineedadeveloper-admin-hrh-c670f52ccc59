<?php
include_once 'vendor/autoload.php';

use Simcify\Application;
use Simcify\Database;
use Simcify\Mail;
use Simcify\Models\EmailModel;


$app = new Application();

//*/5 * * * * cd /var/www/irhliving && php cron_5_minute.php
send_email_all_students();

function send_email_all_students(){
    $custom_email_db = Database::table("custom_mail")->where("check_sent",EmailModel::Pending)->get();
    $cn=0;
    foreach($custom_email_db as $each_row){
        $cn++;
        if ($cn>100)
            break;
        if ($each_row->template_id>0){
            $mail_template = Database::table("email_templates")->find($each_row->template_id);
            $each_row->title=$mail_template->title;
            $each_row->content=$mail_template->content;
        }

        $send= Mail::send(
            $each_row->receiver_email, $each_row->title,
            array(
                "message" => $each_row->content
            )
        );

        $check=$send?EmailModel::Sent:EmailModel::Error;
        $mail_data = array("check_sent" => $check);
        Database::table("custom_mail")->where("id", $each_row->id)->update($mail_data);
    }
}


?>