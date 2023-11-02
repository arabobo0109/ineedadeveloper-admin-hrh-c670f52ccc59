<?php
namespace Simcify\Controllers;

use Simcify\Models\BalanceModel;
use Simcify\Models\CheckoutModel;
use Simcify\Models\InvoiceModel;
use Simcify\Models\OrderModel;
use Simcify\Models\RoomModel;
use Simcify\Models\StudentModel;
use Simcify\Auth;
use Simcify\Database;
use function Simcify\print_r2;

class Checkout{
    public function remaining_balance($user_id){
        $user = Auth::user();
        $student= StudentModel::GetUser($user_id);
        $data = array();

        $data['security_deposit_history'] = Database::table("invoices")->where("student_id", $student->id)->where("security_paid", ">", "0")->get();
        $data['refund_deposit_history'] = Database::table("invoices")->where("student_id", $student->id)->where("security_paid", '<','0')->get();

        $data['user'] = $user;
        $data['student'] = $student;
        $user->page_title = "Final checkout";
//        Customer::addActionLog("Student", "remain balance", "ID:".$student->id." ".$student->fname." ". $student->lname,$student->id);
//        print_r2($data);
        return view('customer/checkout', $data);
    }

    public function refundRemainingBalance(){
        header('Content-type: application/json');
        $user = Database::table("users")->find(input("user_id"));

        $return_balance = input("return_balance");
        $payment_mode=input("payment_mode");

        if(($payment_mode == "Check") || ($payment_mode == "Cash")){
            InvoiceModel::RefundSecurity($user, $return_balance, $payment_mode, $payment_mode."_Number", '******'. $payment_mode);
        }
        elseif($payment_mode == "Credit Card"){
            $security_deposit_history = Database::table("invoices")->where("student_id", $user->id)->where("security_paid", ">", "0")->where('status','Paid')->where('payment_mode','Credit Card')->get();
            $noRefund = true;
            if (empty($security_deposit_history))
                exit(json_encode(responder("error", "No credit card!" ,"No credit card on file. please refund cash","reload()")));
            foreach($security_deposit_history as $invoice){
                if ($invoice->price>=$return_balance){
                    $response=CC::Refund($invoice,$return_balance);
                    if ($response['response']==1){
                        InvoiceModel::RefundSecurity($user, $return_balance, $payment_mode, $invoice->transaction_id, $invoice->card_number);
                    }
                    else
                        exit(json_encode(responder("error", "Declined!", "Credit card declined. please refund using an alternative method. ".$response['responsetext'],"reload()")));
                    $noRefund = false;
                    break;
                }
            }
            if ($noRefund)
                exit(json_encode(responder("error", "Exceeded Amount!" ,$payment_mode . " Method have limit for refunding. Please check Security Deposit History","reload()")));
        }
//        BalanceModel::addBalanceHistory($user,$return_balance, "Refund", "Security Deposit was refunded by ".$payment_mode,$invoice_id,BalanceModel::Security,0);
        Customer::addActionLog("Student", "checkout", $user->fname." ". $user->lname." $".$return_balance." refunded",$user->id);

        exit(json_encode(responder("success", "Remaining Balance" ,"$".$return_balance." was refunded to student!","reload()")));
    }

    public function finalCheckout() {
        $user = Database::table("users")->find(input("user_id"));
        header('Content-type: application/json');

        StudentModel::SetTerminated($user,StudentModel::Checked_Out);
        $checkout = array(
            "student_id" => $user->id,
            "bed_id" => $user->bed_id,
            "total_amount" => $user->balance,
            'company' => $user->company
        );
        Database::table("checkout")->insert($checkout);
        Database::table("beds")->where("id" , $user->bed_id)->update(array('student_id' => 0,'status'=> RoomModel::Dirty));

        $outstanding_balance=$user->security_deposit - $user->balance;
        $message="Remain balance $".$outstanding_balance." was added into outstanding balance (Write Off).";

        if (env('SITE_Portal')){
            $message.=" ".Zk::DisableCard($user);
        }

        exit(json_encode(responder("success", "Checked out" ,$message,"redirect('".url('Customer@profile').$user->id."')")));
    }

    public function getCheckoutHistory(){
        $user = Auth::user();
        if ( $user->role =='user' ){
            return view('checkout_history', array());
        }
        else{
            $checkout_history = array();
            $checkout_history_array = Database::table("checkout")->company()->get();
            foreach($checkout_history_array as $each_checkout_history_array){
                $each_checkout_history = array();
                $each_checkout_history['checkout'] = $each_checkout_history_array;
                $each_checkout_history['student'] = Database::table("users")->where("id" , $each_checkout_history_array->student_id)->first();
                $checkout_history[] = $each_checkout_history;
            }
            $data = array(
                "user" => Auth::user(),
                "checkout_history" => $checkout_history
            );
            return view('checkout_history', $data);
        }
    }
}
