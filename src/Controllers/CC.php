<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use Simcify\Mail;
use Simcify\Models\BalanceModel;
use Simcify\Models\CheckoutModel;
use Simcify\Models\InvoiceModel;
use Simcify\Models\OrderModel;
use Simcify\Models\StudentModel;
use Simcify\Nmi;

define("CC_Log",$_SERVER['DOCUMENT_ROOT']."/log/cc_error.log");
define("Payment_Log",$_SERVER['DOCUMENT_ROOT']."/log/payment.log");

class CC extends Checkin {
    public function submitCard() {
        $user_id=input("payment_user_id");
        $payment_type=input('payment_type',"Credit Card"); //Credit Card or Subscribe, Legacy_pos, Terminal
        $price = input("price_total");
        $parentPage=input("parentPage",'payment'); //  check_pre or payment

        if ($payment_type=="Legacy_pos"){
            $invoice_id=OrderModel::SubmitOrder("Credit Card","T_Legacy_pos",'****Legacy');
            $this->CheckinDone(); //if checkin, go to CheckinDone page
            $result="$".$price." was paid successfully by Terminal App!";
        }
        else{
            $gw = new Nmi();
            $gw->billing['firstname'] = input("fname");
            $gw->billing['lastname'] = input("lname");
            $gw->billing['address1'] = input("address");
            $gw->billing['city'] = input("city");
            $gw->billing['state'] = input("state");
            $gw->billing['zip'] = input("zip");
            $gw->billing['country'] = input("country");
            $gw->billing['phone'] = input("phone");
            $gw->billing['email'] = input("email");

            if ($parentPage=='group_payment'){
                $student= Database::table("employers")->find($user_id);
                $gw->order['orderdescription'] = "Group Payment from ".$student->name;
                $userIds=explode(',',input('selected_user_ids'));
                $price = count($userIds)*input("price_total");

                header('Content-type: application/json');
            }
            else{
                $student= Database::table("users")->find($user_id);
                $gw->order['orderdescription'] = "Payment of ".$student->fname.' '.$student->lname;
            }
            $gw->order['orderid'] = rand(5,100)."-".$student->id;
            $gw->order['shipping'] = 1;

            if ($payment_type=="Terminal"){
                $gw->synchronousTerminal($price);
            }
            else {
                $ccexp = $_POST['ccexpmm'] . $_POST['ccexpyy']; //"1010"  //999
                if ($payment_type == "Credit Card") {
                    $gw->doSale($price, $_POST['ccnumber'], $ccexp, $_POST['cvv']);
                } else {
                    $price = input("weekly_amount");
                    // Get Plan ID
                    $plan_id = input("plan_id");
                    $gw->addSubscription($plan_id, $_POST['ccnumber'], $ccexp);
                }
            }

            $response = $gw->responses;

            error_log("\n".$payment_type." User_id:".$student->id." ".$student->email." price:".$price." ".date("Y-m-d H:i:s")."\n", 3,Payment_Log);
            error_log(print_r($response,true), 3,Payment_Log);
            error_log(print_r($_POST,true), 3,CC_Log);
            $invoice_id=0;
            if ($response['response']==1){
                if ($payment_type=="Terminal"){
                    $payment_type="Credit Card";
                    $cardNumber='****Cloud';
                }
                else
                    $cardNumber=str_pad(substr($_POST['ccnumber'], -4), strlen($_POST['ccnumber']), '*', STR_PAD_LEFT);
                if (input('payment_type',"Checkout")=='Checkout'){
//                    CheckoutModel::AdditionalPayment($student,$price,$response['transactionid'],$cardNumber);
                }
                else{
                    if ($parentPage=='group_payment'){
                        foreach ($userIds as $user_id){
                            OrderModel::SubmitOrder("Credit Card",$response['transactionid'],$cardNumber,$user_id,'by_employer');
                        }
                    }
                    else{
                        $invoice_id=OrderModel::SubmitOrder($payment_type,$response['transactionid'],$cardNumber);
                        if ($payment_type=="Subscribe"){
                            // Save subscribe ID
                            $subscribe_id = $response['subscription_id'];
                            $nmi_plan_id= Database::table("nmi_plan")->where("plan_id" , $plan_id)->first();

                            $s = array(
                                "subscription_id" => $subscribe_id,
                                "is_subscribe" => 1,
                                "nmi_plan_id" => $nmi_plan_id->id,
                            );
                            StudentModel::Update($user_id,$s);

                            $invoice = Database::table("invoices")->where("student_id" , $user_id)->last();
                            Database::table("invoices")->where("id" , $invoice->id)->update(array('nmi_plan_id' => $plan_id));
                        }
                        $this->CheckinDone(); //if checkin, go to CheckinDone page
                    }
                }
                $result="$".$price." was paid successfully by ".$payment_type.". Transaction Id is ".$response['transactionid'];
                if ($parentPage=='group_payment')
                    exit(json_encode(responder("success", "Employer Payment" ,$result,"redirect('".url('Employer@get')."')")));
            }
            else{
                $result="Failed! ".$response['responsetext'];
                if ($parentPage=='group_payment')
                    exit(json_encode(responder("error", "Employer Payment" ,$result)));
            }
        }

        $page="confirm.php";
        $student= Database::table("users")->find($user_id);
        $student->invoice_id=$invoice_id;
        $user = Auth::user();
        return view($parentPage, compact("user","page","result","student"));
    }

// from balance history and transaction history
    public function refundInvoice() {
        header('Content-type: application/json');

        $message="";
        $balance_id=input("refund_balance_id",0);
        $amount=input('refund_balance_amount',0);

        if ($balance_id>0){  //balance id partial refund
            $balance=Database::table("balance_history")->find($balance_id);
            if ($amount<1 || $amount>(-$balance->amount))
                exit(json_encode(responder("error", "Declined!", "Please input corrected amount.")));
            $invoice_id=$balance->invoice_id;
            $invoice= Database::table("invoices")->find($invoice_id);
            $balance->amount=-$amount; // real refund amount
            $balances=[$balance];
        }
        else{  // invoice refund
            $invoice_id=input("invoice_id");
            $invoice= Database::table("invoices")->find($invoice_id);
            if ($amount<1 || $amount>$invoice->price)
                exit(json_encode(responder("error", "Declined!", "Please input corrected amount.")));
            $balance_history=Database::table("balance_history")->where("invoice_id" , $invoice_id)->get();
            $balances=[];
            $diff_amount=$amount;
            foreach ($balance_history as $item){
                $diff_amount+=$item->amount;  //$item->amount=-135
                $balances[] = $item;  // Add $item to the $balances array
                if ($diff_amount<0){
                    $item->amount=$item->amount-$diff_amount; //$item->amount should be negative numbers
                    break;
                }
            }

            if ($diff_amount>0){
                $amount-=$diff_amount;
                $message="Other $".$diff_amount." is can't be refunded";
            }
        }

        if ($invoice->payment_mode=="Credit Card"){
            $response=CC::Refund($invoice,$amount);
            if ($response['response']!=1)
                exit(json_encode(responder("error", "Declined!", "Credit card declined. please refund using an alternative method. ".$response['responsetext'],"reload()")));
        }
        //Success Refunded
        $student= Database::table("users")->find($invoice->student_id);
        foreach ($balances as $item){
            BalanceModel::refund($item,$student);
        }
        if ($balance_id==0){ // invoice refund
            Database::table("invoices")->where("id" , $invoice->id)->update(array('status' => 'Refunded'));
        }
        InvoiceModel::addInvoice($student, -$amount, $invoice->payment_mode, "Refunded", $invoice->transaction_id, $invoice->card_number,0,0,0,0,0,"by_admin");
        $message=$invoice->payment_mode." Payment $".$amount." was successfully refunded.".$message;
        Customer::addActionLog("Student", "Refund", $student->fname." ".$message,$student->id);
        exit(json_encode(responder("success", "Refunded!", $message,"reload()")));
    }

    Static public function Refund($invoice,$price) {
        $gw = new Nmi();
        $gw->setLogin();
        $gw->doRefund($invoice->transaction_id,$price);
        $response = $gw->responses;
        error_log(print_r($response,true), 3,Payment_Log);
        return $response;
    }
}
