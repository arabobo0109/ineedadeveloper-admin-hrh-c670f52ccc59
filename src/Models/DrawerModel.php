<?php
namespace Simcify\Models;

use Simcify\Mail;
use Simcify\Auth;
use Simcify\Database;


class DrawerModel {
    public static function CloseBatch($open_batch,$close_amount){

        $mail_content='';
        $total_transaction = InvoiceModel::getDrawerTotalByDate($open_batch);
        if($open_batch->user_id==1 && $close_amount == 0){  //student site (auto close at midnight)
            $close_amount = $total_transaction;
            $mail_content='Daily ';
        }
        $difference = $open_batch->open_amount + $total_transaction - $close_amount;

        $close_batch_data = array(
            "closing_amount" => $close_amount,
            "difference" => $difference,
            "total_credit" => InvoiceModel::getDrawerTotalCredit($open_batch),
            "end_time" => date("Y-m-d H:i:s"),
            "status" => "closed"
        );
        Database::table("drawer_batch")->where("id" , $open_batch->id)->update($close_batch_data);

        $mail_content =$mail_content . "Drawer ".$open_batch->drawer_number." was closed <br>Closed Time : " . date("Y-m-d H:i:s") . "<br>Total transaction is <strong>$" . $total_transaction."</strong><br>Closing Amount is <strong>$".$close_amount. "</strong><br>Difference amount is <strong>$" . $difference."</strong><br>";

        $mail_array=array(
            "title" => env("Site_Name"),
            "subtitle" => "Click the link below to check the closed drawer transaction.",
            "buttonText" => "Drawer Details",
            "buttonLink" => env("APP_URL")."/drawer/".$open_batch->id,
            "message" => $mail_content
        );

        if (strpos($mail_content,'Daily ')){
            Mail::send(
                env("DrawerReportMail1"), "Drawer Report",
                $mail_array,
                "withbutton"
            );
            Mail::send(
                env("DrawerReportMail2"), "Drawer Report",
                $mail_array,
                "withbutton"
            );
        }
        else{
            $user=Auth::user();
            if (isset($user->email)){
                Mail::send(
                    $user->email, "Drawer Report",
                    $mail_array,
                    "withbutton"
                );
            }
        }
        return $open_batch->drawer_number;
    }

    public static function CreateBatch($open_amount=0,$user_id=1){
        $open_batch = Database::table("drawer_batch")->company()->last();

        $new_drawer_number = $open_batch->drawer_number + 1;
        $create_batch_data = array(
            "user_id" => $user_id,
            "open_amount" => $open_amount,
            "drawer_number" => $new_drawer_number,
            "start_time" => date("Y-m-d H:i:s"),  // Calling time of server
            "status" => "open",
            'company' => Auth::cid()
        );
        Database::table("drawer_batch")->insert($create_batch_data);
        return $new_drawer_number;
    }

    static public function AddCloseAmount($id, $closeAmount){
        $close_batch_data = array(
            "closing_amount" => $closeAmount
        );
        Database::table("drawer_batch")->where("id" , $id)->update($close_batch_data);
    }
}