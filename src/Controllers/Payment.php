<?php
namespace Simcify\Controllers;

use Simcify\Date;
use Simcify\Models\BalanceModel;
use Simcify\Models\CompanyModel;
use Simcify\Models\OrderModel;
use Simcify\Models\ReportModel;
use Simcify\Models\StudentModel;
use Simcify\Nmi;
use Simcify\Mail;
use Simcify\Auth;
use Simcify\Database;
use Simcify\Models\PaymentModel;

class Payment extends Checkin {
// from student
    public function payment() {
        return $this->showPaymentPage(Auth::user()->id);
    }

    public function pay_at_checkin() {
        $user = Auth::user();
        $student=$user;
        $page="confirm.php";
        $result="To pay at checkin, please meet a cashier at window!";
        $ad=array(
            'author_id'=>$user->id,
            'author_name'=>$user->fname.' '.$user->lname,
            'student_id'=>$user->id,
            'type'=>'external',
            'content'=>'I want to pay at checkin.',
            'company' => Auth::cid()
        );
        Database::table('notes')->insert($ad);
        return view('payment', compact("user","page","result","student"));
    }
// After select credit or paypal from checkin or take payment
    public function submitPaymentMode() {
        if (input("payment_option","notCheckin")=="checkin"){  //from checkin page
            $parentPage='payment/checkin_pre';
            $page=input("payment_type","Cash");
            if($page=="cashier_desk"){
                $page="confirm.php";
                $result="To make your payment with cash,please see cashier at window!";
                $checkin_done  = "done";
                return view("payment/checkin_pre", compact("checkin_done", "page", "result"));
            }
            else{ // $page == "Credit Card" and Paypal
                $student= StudentModel::GetUser(input("payment_user_id"));
				return view('payment/checkin_pre', compact("page","student", "parentPage"));
			}
        }
        else  //from take payment
            return $this->showPaymentPage(input("payment_user_id"));
    }
//by admin
    public function take_payment() {
        return $this->showPaymentPage(input("customerid"));
    }

    public function showPaymentPage($user_id) {  //show current payment page or submit payment by credit, check, cash
        $student= StudentModel::GetUser($user_id);
        $page = input("payment_type","select");
        $user = Auth::user();

        $parentPage="payment";
        $student_room_fee = PaymentModel::getStudentRoomFee($student);

        if ($page == 'select'){ // take payment by admin. or .. ?
            $companies = CompanyModel::GetCompany();
            $owed_list=BalanceModel::getOwed($student->id);
            return view('make_payment', compact("user", "companies", "student_room_fee", "student","owed_list"));
        }
        else{  //cash or check, credit card, Holding
            if ($page=='Cash' || $page=='Check' || $page=='Holding' || $page=='Credit' || $page=='Deposit'){
                $invoice_id=0;
                $price_total=input("price_total");
                if ($page=='Holding' && $price_total>$student->holding_balance){
                    $result="Failed! The holding balance is insufficient.";
                    $message="holding balance is ".$student->holding_balance." tried to pay ".$price_total.' from user_id:'.$student->id;
                    ReportModel::SendError($message);
                    Customer::addActionLog("Payment", "Banned Holding", $message,$student->id);
                }
                elseif ($page=='Deposit' && $price_total>$student->security_deposit){
                    $result="Failed! The security deposit balance is insufficient.";
                    $message="Security deposit is ".$student->security_deposit." tried to pay ".$price_total.' from user_id:'.$student->id;
                    ReportModel::SendError($message);
                    Customer::addActionLog("Payment", "Banned Holding", $message,$student->id);
                }
                else{
                    $invoice_id=OrderModel::SubmitOrder($page,$page."_Number",'******'.$page);
                    $this->CheckinDone(); //if checkin, go to CheckinDone page
                    $result="$".$price_total." was paid successfully by ".$page."!";
                }
                $page="confirm.php";
                //get new data for user
                $student= StudentModel::GetUser(input("payment_user_id"));
                $student->invoice_id=$invoice_id;
                return view('payment', compact("user","page","result", "student"));
            }
            elseif($page == "Subscribe"){
                $company = CompanyModel::GetCompany();
                $company_weekly_fee = $company->weekly;
                $gw = new Nmi();
                $gw->setLogin();

                // Create Default PlanID if no in db
                $nmi_db_default = Database::table("nmi_plan")->where("plan_id" , env("NMI_PLAN_DEFAULT_ID"))->first();
                if(empty($nmi_db_default)){
                    $plan_name = "Weekly Room Rate Subscription for Default";
                    $gw->addPlan($company_weekly_fee,env("NMI_PLAN_DEFAULT_ID"), $plan_name);
                    $response = $gw->responses;
                    if($response['response'] == 1){
                        $plan = array(
                            "plan_id" => env("NMI_PLAN_DEFAULT_ID"),
                            "amount" => $company_weekly_fee,
                        );
                        Database::table("nmi_plan")->insert($plan);
                    }
                    else{
                        $result="Failed! .".$response['responsetext'];
                        print_r($result);
                        exit(0);
                    }
                }
                $plan_id = env("NMI_PLAN_DEFAULT_ID");

                $weekly_amount = $student_room_fee['week_fee'];
                if($company_weekly_fee != $weekly_amount){
                    $plan_id = env("NMI_PLAN_PREFIX")."_".$student->id."_plan";
                    $plan_name = "Weekly Room Rate Subscription for ". $student->fname;

                    $nmi_db = Database::table("nmi_plan")->where("plan_id" , $plan_id)->first();

                    if(empty($nmi_db)){
                        $gw->addPlan($weekly_amount,$plan_id, $plan_name);
                        $response = $gw->responses;

                        if($response['response'] == 1){
                            $invoice = array(
                                "plan_id" => $plan_id,
                                "amount" => $weekly_amount,
                            );
                            Database::table("nmi_plan")->insert($invoice);
                        }
                        else{
                            $result="Failed! .".$response['responsetext'];
                            print_r($result);
                            exit(0);
                        }
                    }
                }
                return view('payment', compact("user","page","price_total", "parentPage", "weekly_amount","student","plan_id"));
            }
            elseif($page == "Unsubscribe"){
                $gw = new Nmi();
                $gw->setLogin();

                $gw->deleteSubscription($student->subscription_id);
                $response = $gw->responses;
                if($response['response'] == 1){
                    $s = array(
                        "subscription_id" => "",
                        "is_subscribe" => 0,
                        "nmi_plan_id" => 0,
                    );
                    StudentModel::Update($student->id, $s);
                    $result="Subscription Success Canceled!";
                }
                else {
                    $result = "Failed! ." . $response['responsetext'];
                }
                $page="confirm.php";
                $parentPage=input("parentPage",'payment'); //  check_pre or payment

                return view($parentPage, compact("user","page","result", "student"));
            }
            else   // Credit Card, Terminal
                return view('payment', compact("user","page","parentPage","student"));
        }
    }

    function UpdatePlanNMI($mni_plan, $weekly_amount){
        $gw = new Nmi();
        $gw->setLogin();
        $gw->editPlan($mni_plan , $weekly_amount);
        $response = $gw->responses;
        if($response['response'] == 1){
            $nmi = array(
                "amount" => $weekly_amount,
            );
            Database::table("nmi_plan")->where("plan_id" , $mni_plan)->update($nmi);
        }
    }

    public function history() {
        $user = Auth::user();
        $invoices = Database::table("invoices")->company();
        if ($user->role == "user") {
            $invoices =$invoices->where("student_id", $user->id);
        }
        $invoices =$invoices->orderBy('created_at',false)->get();
        return view('payment/history', compact("user", "invoices"));
    }

    public function checkin(){
    	$checkin_token = $_REQUEST["token"];

    	if(isset($_REQUEST["lname"])){
			$current_user = Database::table("users")->where("lname", $_REQUEST["lname"])->where("pin", $_REQUEST["pin"])->first();
			if(empty($current_user))
				echo "-1";
			else
				print_r($current_user->id);
			exit(0);
		}
		if(isset($_REQUEST["reset_pin"])){
			$reset_pin = rand(100000, 999999);
			$current_user = Database::table("users")->where("lname", $_REQUEST["reset_lname"])->where("email", $_REQUEST["reset_email"])->first();
			if(empty($current_user))
				echo "-1";
			else{
				$user = Database::table("users")->where("token", $_REQUEST["token"])->first();
				Mail::send(
	                $user->email, "Pin Changed",
	                array(
	                    "title" => "Pin Changed",
	                    "subtitle" => "Please review your new Pin",
	                    "buttonText" => "Check-in now",
	                    "message" => "Your new Pin code is ". $reset_pin
	                )
	            );
	            
				$data = array('pin' => $reset_pin);
	            Database::table('users')->where("token", $_REQUEST["token"])->update($data);
				echo "1";
			}
			exit(0);
		}
		if(isset($_REQUEST["uid"])){
            $user = Database::table("users")->find($_REQUEST["uid"]);
			$student=$user;
            $owed_list=BalanceModel::getOwed($student->id);
			return view('payment/checkin_payment', compact("user","student","owed_list"));
		}			
        else{
        	return view('payment/checkin_pre', compact("checkin_token"));
		}	      
    }
}
