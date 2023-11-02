<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use Simcify\Models\BalanceModel;
use Simcify\Models\OrderModel;

class Fine extends Admin {
    public function get() {
    	$data = array(
    		"user" => Auth::user(),
            "fine_fees" => Database::table("fine_fees")->company()->get()
        );    	
        return view('fine_fees', $data);
    }

    /**
     * Create business user account
     * 
     * @return Json
     */

    public function create() {
        header('Content-type: application/json');
        $ad = array(
            "type" => $_POST["type"],
            "amount" => $_POST["amount"],
            'company' => Auth::cid()
        );
        Database::table("fine_fees")->insert($ad);

        // Action Log
        Customer::addActionLog("Fine", "Create Fine", "Created Fine : ". input("type"));

        exit(json_encode(responder("success","Alright", "Fine Fee successfully created","reload()")));
    }

    public function createByAjax() {
        header('Content-type: application/json');
        $ad = array(
            "type" => $_POST["type"],
            "amount" => $_POST["amount"],
            'company' => Auth::cid()
        );
        Database::table("fine_fees")->insert($ad);
        exit(json_encode(Database::table("fine_fees")->company()->last()));
    }

    public function delete() {
        // Action Log
        $fine = Database::table("fine_fees")->where("id", input("fineid"))->first();
        Customer::addActionLog("Fine", "Delete Fine", "Deleted Fine : ". $fine->type);

        Database::table("fine_fees")->where("id", input("fineid"))->delete();
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Fine Fee Deleted!", "Fine Fee successfully deleted.","reload()")));
    }

    public function updateview() {
        $data = array(
                "fine_fees" => Database::table("fine_fees")->where("id", input("fineid"))->first()
            );
        return view('extras/updatefine', $data);
    }

    public function updateFine() {
        $dd=array('type'=>input('type'),
            'amount'=>input('amount')
            );
        Database::table("fine_fees")->where("id" , input("fineid"))->update($dd);

        // Action Log
        Customer::addActionLog("Fine", "Edit Fine", "Edited Fine : ". input("type"));
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Alright!", "Fine Fee was successfully updated","reload()")));
    }

    public function updateAddFine(){ //to the student
        $user_id=input("user_id");
        $note=input("note");

        $user = Database::table("users")->find($user_id);
        header('Content-type: application/json');

        $fine_ids = input("fine_ids");
        $fine_amount = 0;
        $message='';
        if ($fine_ids!=null){
            foreach($fine_ids as $each_id){
                $fine_fee = Database::table("fine_fees")->where("id" , $each_id->value)->first();
                $fine_amount += $fine_fee->amount;
                $balance_history = array(
                    "amount" =>$fine_fee->amount,
                    "action" => "Fine",
                    "note" => $fine_fee->type.(empty($note)?'':' ('.$note.')'),
                    "type"=>BalanceModel::Fine
                );
                BalanceModel::insertOwed($user,$balance_history);
            }
            $message='Total Fine $'.$fine_amount.' was added.';
            Customer::addActionLog("Fine", "Add Fine $".$fine_amount." to Student", "Added Fine Fee to " . $user->fname . " " . $user->lname);
        }

//        $credit_amount=input("credit_amount");
//        if ($credit_amount<0){
//            BalanceModel::addBalanceHistory($user,$credit_amount, "Credit Amount", $note,0,BalanceModel::Other);
//            // Action Log
//            $message.='Credit $'.$credit_amount.' was added.';
//            Customer::addActionLog("Credit", "Credited Amount was added", "ID:".$user->id." ". $user->fname ." ". $user->lname." as $".$credit_amount);
//        }

        exit(json_encode(responder("success", "Pre checkout" ,$message,"reload()")));
    }

    public function cancel() {
        $balance_id=input("balance_id",0);
        BalanceModel::Cancel($balance_id);

        header('Content-type: application/json');
        exit(json_encode(responder("success", "The Fee Canceled!", "The Fee was successfully deleted.","reload()")));
    }
}
