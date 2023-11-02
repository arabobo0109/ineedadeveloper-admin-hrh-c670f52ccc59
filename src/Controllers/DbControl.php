<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use Simcify\DocSign;
use Simcify\File;
use Simcify\Models\CompanyModel;
use Simcify\Models\OrderModel;
use Simcify\Models\PaymentModel;
use Simcify\Models\StudentModel;

class DbControl extends Admin{
//db/get_balance
    public function GetBalance(){
        $customers = Database::table("users")->company()->where("role", "user")->get();
        foreach ($customers as $customer) {
//            StudentModel::Update($customer->id,array('balance'=>PaymentModel::getPriceBalance($customer)));

            $balance_history = Database::table("balance_history")->where("student_id", $customer->id)->get();
            $balance=0;
            foreach($balance_history as $each){
                $balance+=$each->amount;
                if ($each->balance!=0){
                    if ($each->balance!=$balance)
                        echo "<br>not same user_id:".$customer->id." ".$each->balance.":".$balance;
                    break;
                }
                Database::table("balance_history")->where("id" , $each->id)->update(array("balance"=>$balance));
            }
            if ($customer->balance!=$balance)
                echo "<br>incorrect user_id:".$customer->id." ".$customer->balance.":".$balance;
        }
        echo "balance history updated";
    }
//https://portal.irhliving.com/db/add_order_and_balancehistory/6050
//check company, and check security, administration fee.
    function addOrderAndBalancehistory($user_id){
        $fees = CompanyModel::GetCompany();
//        $fees->security=0;
//        $fees->administration=25;
//        $fees->laundry=0;
        $users = Database::table("users")->where("id",">=", $user_id)->get();
        foreach($users as $user){
            OrderModel::Add0($user,$fees);
            StudentModel::Update($user->id,array('season'=>'Spring 2023'));
        }
        echo "added";
    }

    //https://portal.irhliving.com/sendDocsign?id=1722&count=1
    function sendDocsign(){
        $id1=input('id');
        $id2=$id1+input('count');
        $users = Database::table("users")->where("id",">=", $id1)->where("id","<", $id2)->get('id','email');
        foreach($users as $user){
            $res=DocSign::sendAgreement($user->email,$user->id);
            echo "\r\n".$user->id.' '.$res;
        }
        echo "\r\ndone!";
    }

    //https://portal.irhliving.com/db/remove_user/2101
    function removeUser($user_id){
        $user = Database::table("users")->find($user_id);
        StudentModel::Remove($user);
        echo "Deleted";
    }

    //https://portal.irhliving.com/db/removeNotArrivedUser?season=Spring 2024
    function removeNotArrivedUser(){
        $season=input('season');
        $users = Database::table("users")->company()->where("season", $season)->where("status",StudentModel::Created)->get();
//        $users = Database::table("users")->company()->where("account_type", 'Test')->get();
        foreach($users as $user){
            echo $user->id." ".$user->fname."<br>";
            StudentModel::Remove($user);
        }
        echo "<br>done!";
    }
}
