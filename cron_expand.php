<?php

include_once 'vendor/autoload.php';

use Simcify\Database;
use Simcify\Models\BalanceModel;
use Simcify\Models\PaymentModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Simcify\Application;
use Simcify\Models\StudentModel;
use Simcify\Mail;
use Simcify\Auth;

$app = new Application();

//0 1 * * * cd /var/www/irhliving && php cron_expand.php
//http://amg.signer/cron_expand.php

$today = date("Y-m-d");

/* Auto expand 1 weeks  */

$companies=Database::table('companies')->get();
foreach ($companies as $company){
    Auth::setCid($company->id);
    $users = Database::table("users")->company()->where("real_end","<=", $today)->where("account_type","Real")->get();
    $mail_content="";
    foreach($users as $student){
        if($student->status==StudentModel::Arrived || $student->status==StudentModel::Extended ){
            $student_room_fee = PaymentModel::getStudentRoomFee($student);
            $room_fee=$student_room_fee['week_fee'];
            $newOrder = array(
                'real_end'=> date('Y-m-d', strtotime($student->real_end. ' + 7 days'))
            );

            $unit=($student->unit!=null)?$student->unit:"no room";
            $mail_content.="Id:".$student->id."     <strong>".$student->fname."</strong>  Holding:$".$student->holding_balance;

            StudentModel::Update($student->id,array('status'=>StudentModel::Extended));

            $balance_history = array(
                "amount" =>$room_fee,
                "action" => "Balance Owed",
                "note" => "Room fee (".$student->real_end.":".$newOrder['real_end'].")",
                "type"=>BalanceModel::Room
            );
            BalanceModel::insertOwed($student,$balance_history);
            $balanceId = Database::table("balance_history")->insertId();

            if ($student->holding_balance>=$room_fee){
                BalanceModel::addHoldingBalance($student, -$room_fee);
                $dd = array(
                    "action" => "Payment with Holding",
                    "note" => "room fee (".$student->real_end.":".$newOrder['real_end'].")"
                );
                BalanceModel::insertPaid($student,$dd,$balanceId,$room_fee);
                $mail_content.=" Automatically Paid ";
            }

            $mail_content.=" Balance:$".$student->balance."<br>";
            StudentModel::Update($student->id,$newOrder);
        }
    }

//alert email
    $mail_content="";
    $users = Database::table("users")->company()->where("status",'Created')->where("bed_id",'>',0)->where("created_at",'<',date('Y-m-d', strtotime($today. ' - 7 days')))->get();
    foreach($users as $user){
        $mail_content.="<br>Id:".$user->id."  Unit: <strong>".$user->unit."</strong> <strong>".$user->fname."</strong>  Assigned at ".$user->created_at;
    }

    $subject="Not arrived student list from ".env("Site_Name");

    if ($mail_content!=""){
        Mail::send(
            env("RoommateRequestMail"), $subject,
            array(
                "message" =>$mail_content
            )
        );
        Mail::send(
            env("Mail_Bill"), $subject,
            array(
                "message" =>$mail_content
            )
        );
    }
}



//if ($mail_content!=""){
//    $mail_content="Lists of students whose Lease end date has been extended by one week today<br><br>".$mail_content;
//    Mail::send(
//        env("DrawerReportMail1"), "Lease expand by one week",
//        array(
//            "message" =>$mail_content
//        )
//    );
//    Mail::send(
//        env("DrawerReportMail2"), "Lease expand by one week",
//        array(
//            "message" =>$mail_content
//        )
//    );
//}
//Mail::send(
//    env("DrawerReportMail3"), "Lease expand by one week",
//    array(
//        "message" =>$mail_content
//    )
//);

?>