<?php
namespace Simcify\Models;
use Simcify\Auth;
use Simcify\Database;
use Simcify\Date;
use Simcify\Mail;
use function Simcify\print_r2;

class OrderModel
{
    const Init='init';
    const Active='active';
    const Closed='closed';

    public function __construct() {
    }

    static public function Add($user,$fees)
    {
        if ($user->account_type == 'Test')
            return;

        $dd = array(
            "amount" =>$fees->security,
            "action" => "Balance Owed",
            "note" => "Security Deposit",
            "type"=>BalanceModel::Security
        );
        if ($fees->security>0)
            BalanceModel::insertOwed($user,$dd);

        if ($fees->administration>0){
            $dd['note']="Administration";
            $dd['amount']=$fees->administration;
            $dd['type']=BalanceModel::Administration;
            BalanceModel::insertOwed($user,$dd);
        }

        if ($fees->laundry>0) {
            $dd['note'] = "Laundry";
            $dd['amount'] = $fees->laundry;
            $dd['type'] = BalanceModel::Laundry;
            BalanceModel::insertOwed($user, $dd);
        }

        $dd['note']="Room fee (1st week)";
        $student_room_fee = PaymentModel::getStudentRoomFee($user);
        $dd['amount']=$student_room_fee['week_fee'];
        $dd['type']=BalanceModel::Room;
        BalanceModel::insertOwed($user,$dd);
        if (!env("SITE_Portal")){
            $dd['note']="Room fee (2nd week)";
            BalanceModel::insertOwed($user,$dd);
        }
    }

    static public function Add0($user,$fees)
    {
        $dd = array(
            "amount" =>$fees->security,
            "action" => "Balance Owed",
            "note" => "Security Deposit",
            "type"=>BalanceModel::Security
        );
        if ($fees->security>0)
            BalanceModel::insertOwed($user,$dd);

        if ($fees->administration>0){
            $dd['note']="Administration";
            $dd['amount']=$fees->administration;
            $dd['type']=BalanceModel::Administration;
            BalanceModel::insertOwed($user,$dd);
        }

        if ($fees->laundry>0) {
            $dd['note'] = "Laundry";
            $dd['amount'] = $fees->laundry;
            $dd['type'] = BalanceModel::Laundry;
            BalanceModel::insertOwed($user, $dd);
        }
    }

    //$paymentMode=Credit Card,Subscribe,Cash,Holding  ...
    static public function SubmitOrder($paymentMode, $transaction, $card,$student_id=null,$payment_option=null){
        if ($student_id==null) { // normal payment        {
            $student_id=input("payment_user_id");
            $payment_option=input('payment_option');  //by_admin, by_user, by_employer
            $owed_list=isset($_POST['owed_check'])?$_POST['owed_check']:[];
        }
        else{ // Group pay
            $owed_list=BalanceModel::getOwed($student_id);
        }
        $priceTotal=input("price_total");
        $paymentUser = Database::table("users")->find($student_id);

        $securityPaid = 0;
        $roomPaid = 0;
        $administrationPaid =0;
        $laundryPaid = 0;
        $holding_amount=input('holding_amount',0);

        $invoice_id=0;
        if ($paymentMode=='Deposit'){
            StudentModel::UpdateSecurityDeposit($paymentUser,-$priceTotal);
            $dd = array(
                "amount" =>$priceTotal,
                "action" => "Balance Owed",
                "note" => "Security Deposit (Used)",
                "type"=>BalanceModel::Security
            );
            BalanceModel::insertOwed($paymentUser,$dd);
        }
        else
        {
            $invoice_id=InvoiceModel::addInvoice($paymentUser, $priceTotal, $paymentMode, "Paid", $transaction, $card,0,0,0,0,$holding_amount,$payment_option);  //this is fake values. below is correct value

            if ($paymentMode=='Holding'){
                BalanceModel::addHoldingBalance($paymentUser, -$priceTotal);
            }
        }

        //new payment system

        $finePaid=$otherPaid=0;
        $totalCheck=$holding_amount;
        foreach ($owed_list as $index =>$owed_id){
//            print_r2($owed_id);
            if (isset($_POST['owed_amount'])){
                $paid_amount= $_POST['owed_amount'][$index];
                $totalCheck+=$paid_amount;
                $balance_history=Database::table("balance_history")->find($owed_id);
            }
            else{  // Employer payment (Group payment)
                $balance_history=$owed_id;
                $owed_id=$owed_id->id;
                $paid_amount= $balance_history->owed_amount;
                if ($priceTotal>$paid_amount)
                    $priceTotal-=$paid_amount;
                elseif($priceTotal>0){
                    $paid_amount=$priceTotal;
                    $priceTotal=0;
                }
                else
                    break;

            }
            if ($paid_amount>0){
                $dd = array(
                    "action" => "Payment with ".$paymentMode,
                    "note" => $balance_history->note,
                    "invoice_id" => $invoice_id
                );
                BalanceModel::insertPaid($paymentUser,$dd,$owed_id,$paid_amount);

                if ($balance_history->type==BalanceModel::Security)
                    $securityPaid+=$paid_amount;
                else if ($balance_history->type==BalanceModel::Room)
                    $roomPaid+=$paid_amount;
                else if ($balance_history->type==BalanceModel::Administration)
                    $administrationPaid+=$paid_amount;
                else if ($balance_history->type==BalanceModel::Laundry)
                    $laundryPaid+=$paid_amount;
                else if ($balance_history->type==BalanceModel::Fine)
                    $finePaid+=$paid_amount;
                else if ($balance_history->type==BalanceModel::Other)
                    $otherPaid+=$paid_amount;
            }
        }

        if ($payment_option=='by_employer' && $priceTotal>0){
            $holding_amount=$priceTotal;
        }

        if ($holding_amount>0){
            BalanceModel::addHoldingBalance($paymentUser, $holding_amount);
            BalanceModel::addBalanceHistory($paymentUser, -$holding_amount, "Payment with ".$paymentMode, "Holding Balance Added ($".$paymentUser->holding_balance.")",$invoice_id,BalanceModel::Holding);
        }

        if ($invoice_id>0){
            $dd = array(
                "room_paid" => $roomPaid,
                "administration_paid" => $administrationPaid,
                "security_paid" => $securityPaid,  //>0
                "laundry_paid" => $laundryPaid,
                "fine_paid" => $finePaid,
                "other_paid" => $otherPaid,
                "transaction_id" => str_replace('Number',$invoice_id,$transaction)
            );
            Database::table("invoices")->where('id',$invoice_id)->update($dd);
            StudentModel::UpdateSecurityDeposit($paymentUser,$securityPaid);  //$securityPaid>0
        }
        //check total issue
        if ($payment_option=='by_user' && $totalCheck!=$priceTotal)
            ReportModel::SendError("Total is Different by user payment price total:".$priceTotal." total check:".$totalCheck);
        return $invoice_id;
    }

    static public function ChangeOrderDate($user,$start) {
        StudentModel::Update($user->id,array(
            'status'=>StudentModel::Arrived,
            'lease_start' => date('Y-m-d', strtotime($start)),
            'real_end'=>Date::GetLeaseEnd($start)
        ));
    }
}