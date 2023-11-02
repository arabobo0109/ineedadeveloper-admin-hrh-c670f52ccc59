<?php

include_once 'vendor/autoload.php';

use Simcify\Database;
use Simcify\Application;
use Simcify\Date;
use Simcify\File;
use Simcify\Models\BalanceModel;
use Simcify\Models\InvoiceModel;
use Simcify\Models\StudentModel;

$app = new Application();
//http://amg.signer/db_script.php
//https://portal.irhliving.com/db_script.php

//UpdateBalanceStatus();
//function UpdateBalanceStatus()
//{
//    $balances = Database::table("balance_history")->where('status','None')->get();
//    foreach ($balances as $balance) {
//        $row=StudentModel::GetUser($balance->student_id);
//        if ($row->checked_out_at<$balance->created_at && $row->checked_out_at!='2022-01-01 00:00:00')
//            $status=StudentModel::Terminated;
//        elseif ($balance->created_at<$row->lease_start)
//            $status=StudentModel::Created;
//        elseif ($balance->created_at<Date::GetLeaseEnd($row->lease_start))
//            $status=StudentModel::Arrived;
//        else
//            $status=StudentModel::Extended;
//        Database::table("balance_history")->where('id', $balance->id)->update(array('status' => $status));
//    }
//    echo "<br>done";
//}


//function updateUserSecurityDepositValue(){
//    $users = Database::table("users")->where("role", "user")->get();
//    foreach($users as $user){
//        StudentModel::Update($user->id,array('security_deposit'=>InvoiceModel::getSecurityPaid($user->id)));
//    }
//    echo "done!";
//}
//
//function AddBalanceMadeBy(){
//    $invoices=Database::table("invoices")->get();
////    $cn=0;
//    foreach($invoices as $item){
////        $cn++;
//        $balances = Database::table("balance_history")->where("invoice_id", $item->id)->get();
//        if ($item->payment_option=='by_user')
//            $made_by='Student';
//        else{
//            $user=Database::table('users')->find($item->paid_user_id);
//            $made_by=$user->fname;
//        }
//        foreach($balances as $balance){
//            Database::table("balance_history")->where('id',$balance->id)->update(array('made_by'=>$made_by));
//        }
////        if ($cn>10)
////            break;
//    }
//    echo "<br>done";
//}
//
//
//function add_drawer_number()
//{
//    $batches = Database::table("drawer_batch")->get();
//    foreach ($batches as $batch){
//        $end_time = $batch->end_time;
//        if($batch->status == "open")
//            $end_time = date("Y-m-d H:i:s");
//        $invoices = Database::table("invoices")->where("created_at",">=", $batch->start_time)->where("created_at","<=", $end_time)->get();
//        if(!empty($invoices)){
//            foreach($invoices as $invoice){
//                if (InvoiceModel::IsBatch($invoice,$batch->user_id))
//                    Database::table("invoices")->where("id" , $invoice->id)->update(array('drawer_number' => $batch->drawer_number));
//            }
//        }
//    }
//}
//

addBeds();

function addBeds()
{
    $rooms = Database::table("rooms")->where("id",'>',1224)->get('id');
    foreach($rooms as $room){
        $dd = array(
            "name" => 'A',
            "room_id" => $room->id
        );
        Database::table("beds")->insert($dd);
        $dd['name']='B';
        Database::table("beds")->insert($dd);
        $dd['name']='C';
        Database::table("beds")->insert($dd);
        $dd['name']='D';
        Database::table("beds")->insert($dd);
        echo "<br> room id:".$room->id;
    }
    echo "<br> Done";

}
//
//function updateInvoicePaidFromBalanceHistory()
//{
//    $balances = Database::table("balance_history")->where("owed_id",'>',0)->where('invoice_id','>',0)->get();
//    foreach($balances as $balance_history){
//        $invoice = Database::table("invoices")->find($balance_history->invoice_id);
//        if ($balance_history->type == BalanceModel::Security)
//            $dd=array('security_paid'=>$invoice->security_paid-$balance_history->amount);
//        else if ($balance_history->type == BalanceModel::Room)
//            $dd=array('room_paid'=>$invoice->room_paid-$balance_history->amount);
//        else if ($balance_history->type == BalanceModel::Administration)
//            $dd=array('administration_paid'=>$invoice->administration_paid-$balance_history->amount);
//        else if ($balance_history->type == BalanceModel::Laundry)
//            $dd=array('laundry_paid'=>$invoice->laundry_paid-$balance_history->amount);
//        else if ($balance_history->type == BalanceModel::Fine)
//            $dd=array('fine_paid'=>$invoice->fine_paid-$balance_history->amount);
//        else{
//            $dd=array('other_paid'=>$invoice->other_paid-$balance_history->amount);
//            echo "<br>No type? :".$balance_history->type."  <br>";
//        }
//
//        Database::table("invoices")->where('id', $invoice->id)->update($dd);
//
//        echo $balance_history->student_id." ".$balance_history->type." ".$balance_history->amount." <br>";
//    }
//}
//
//function updateUserStatus(){
//    $users = Database::table("users")->where("role", "user")->where("status", StudentModel::Created)->get();
//    foreach($users as $user){
//        $request = Database::table("invoices")->where("student_id", $user->id)->first();
//        if ($request){
//            StudentModel::Update($user->id,array('status'=>StudentModel::Arrived));
//        }
//    }
//    echo "done!";
//}
//
//function updateUserSignStatus(){
//    $users = Database::table("users")->where("role", "user")->get();
//    foreach($users as $user){
//        $request = Database::table("requests")->where("receiver", $user->id)->first();
//        if ($request){
////            if ($request->status==StudentModel::Pending)
////                StudentModel::Update($user->id,array('sign_status'=>StudentModel::Sent));
//            if ($request->status==StudentModel::Signed)
//                StudentModel::Update($user->id,array('sign_status'=>StudentModel::Signed));
//        }
//    }
//    echo "done!";
//}
?>