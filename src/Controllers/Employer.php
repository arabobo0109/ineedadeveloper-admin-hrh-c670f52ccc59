<?php
namespace Simcify\Controllers;

use Google\Service\Analytics\Resource\Data;
use Simcify\Auth;
use Simcify\Database;
use Simcify\Models\BalanceModel;
use Simcify\Models\CheckoutModel;
use Simcify\Models\OrderModel;
use Simcify\Models\PaymentModel;
use Simcify\Models\StudentModel;

class Employer extends Admin {
    public function get() {
    	$employers = Database::table("employers")->company()->orderBy('name', 'asc')->get();
    	foreach ($employers as $employer){
    	    $employer->balance=Employer::totalBalance($employer->id);
    	}
    	$data = array(
    			"user" => Auth::user(),
    			"employers" => $employers
    		);
        return view('employers', $data);
    }

    public static function totalBalance($employer_id){
        $balance=Database::table('users')->where('employer',$employer_id)->where('status','!=',StudentModel::Terminated)->where('balance','>',0)->sum('balance','balance');
        return $balance[0]->balance+0;
    }

    /**
     * Create business user account
     * 
     * @return Json
     */

    public function create() {
        header('Content-type: application/json');
        $employer_data = array(
            "name" => $_POST["name"],
            "company_info" => $_POST["company_info"],
            "email" => $_POST["email"],
            'company' => Auth::cid()
        );

        Database::table("employers")->insert($employer_data);

        // Action Log
        Customer::addActionLog("Employer", "Create Employer", "Created a Employer : ". $_POST["name"]);

        exit(json_encode(responder("success","Alright", "Employer successfully created","reload()")));
    }

    public function delete() {
        // Action Log
        $users=Database::table('users')->where('employer',input("employerid"))->get();
        header('Content-type: application/json');
        if (count($users)>0)
            exit(json_encode(responder("error", "Employer can't be deleted!", "There are ".count($users)." users which linked this employer")));

        $employer = Database::table("employers")->find(input("employerid"));

        Customer::addActionLog("Employer", "Delete Employer", "Deleted a Employer: ". $employer->name);

        Database::table("employers")->where("id", input("employerid"))->delete();


        exit(json_encode(responder("success", "Employer Deleted!", "Employer successfully deleted.","reload()")));
    }

    public function updateview() {
        $data = array(
                "employer" => Database::table("employers")->where("id", input("employerid"))->first()
            );
        return view('extras/update_employer', $data);
    }

    public function update() {
    	
        foreach (input()->post as $field) {
            if ($field->index == "csrf-token" || $field->index == "employerid") {
                continue;
            }
            Database::table("employers")->where("id" , input("employerid"))->update(array($field->index => escape($field->value)));
        }

        $employer = Database::table("employers")->where("id", input("employerid"))->first();

        // Action Log
        Customer::addActionLog("Employer", "Edit Employer", "Changed Employer information: ". $employer->name);

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Alright!", "Employer was successfully updated","reload()")));
    }

    public function employer_payment(){
        $data['students'] = Database::table("users")->where('employer', input("employer_id"))->where('status','!=',StudentModel::Terminated)->where('balance','>',0)->get();
        foreach ($data['students'] as $user){
            $user->balance_owed=BalanceModel::getTotalOwed($user->id);
            $user->security_owed=BalanceModel::getTotalOwed($user->id,BalanceModel::Security);
            $user->admin_owed=BalanceModel::getTotalOwed($user->id,BalanceModel::Administration);
            $user->laundry_owed=BalanceModel::getTotalOwed($user->id,BalanceModel::Laundry);
            $user->room_owed=BalanceModel::getTotalOwed($user->id,BalanceModel::Room);
        }
        $data['employer_id']=input("employer_id");
        return view('payment/employer_payment', $data);
    }

    public function employer_payment_cash(){ //check
        header('Content-type: application/json');
        $page='Check';
        $userIds=explode(',',input('selected_user_ids'));
        foreach ($userIds as $user_id){
            OrderModel::SubmitOrder($page,$page."_Number",'******'.$page,$user_id,'by_employer');
        }
        exit(json_encode(responder("success", "Employer Payment" ,"All student balances were paid by the employer.","reload()")));
    }

    public function employer_payment_credit_card(){
        $data['user'] = Auth::user();
        $data['student'] = $data['user'];
        $userIds=explode(',',input('selected_user_ids'));
        $data['group_total'] = count($userIds)*input("price_total");
        $data['selected_user_ids']=input('selected_user_ids');
        $data['parentPage']='group_payment';

        return view('payment/group_payment', $data);
    }

}
