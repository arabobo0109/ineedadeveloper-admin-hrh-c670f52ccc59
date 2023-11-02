<?php
namespace Simcify\Models;

use Simcify\Auth;
use Simcify\Date;
use Simcify\Mail;
use Simcify\Database;
use function DI\object;
use function Simcify\print_r2;


class InvoiceModel {

    static public function RefundSecurity($user,$price,$payment_mode,$transaction,$card) {
        InvoiceModel::addInvoice($user, -$price, $payment_mode, "Refunded", $transaction, $card,0,0,-$price,0,0,"by_admin");
        StudentModel::UpdateSecurityDeposit($user,-$price);  //$securityPaid>0
    }

    static public function addInvoice($user,$price,$paymentMode,$status,$transactionId,$card,$roomPaid,$administrationPaid,$securityPaid,$laundryPaid,$holding_paid,$payment_option) {
        $invoice = array(
            "price" => $price,
            "name" =>  filter_var($user->fname . ' ' . $user->lname, FILTER_SANITIZE_STRING),
            "email" => filter_var($user->email, FILTER_SANITIZE_EMAIL),
            "transaction_id" => $transactionId,
            "student_id" => $user->id,
            "payment_mode" => $paymentMode,
            "status" => $status,  //"Paid","Refunded"
            "room_number" => $user->room_id,
            "bed_id" => $user->bed_id,
            "card_number" =>$card,
            "paid_user_id" => Auth::user()->id,
            "room_paid" => $roomPaid,
            "administration_paid" => $administrationPaid,
            "security_paid" => $securityPaid,
            "laundry_paid" => $laundryPaid,
            "holding_paid" => $holding_paid,
            "payment_option"=>$payment_option,
            "created_at"=>date("Y-m-d H:i:s"),  // Calling time of server
            'company' => $user->company
        );
        $invoiceObj=(object)$invoice;
        $batches = Database::table("drawer_batch")->company()->where("status", "open")->get();
        foreach ($batches as $batch){
            if (InvoiceModel::IsBatch($invoiceObj,$batch->owner_id)){
                $invoice['drawer_number']=$batch->drawer_number;
                break;
            }
        }
        Database::table("invoices")->insert($invoice);
        $invoice_id=Database::table("invoices")->insertId();
        if ($payment_option!="by_employer")
            Mail::send(
                $user->email, "Payment Notification from ".env('Site_Name'),
                array(
                    "title" => $paymentMode." $".$price." was ".$status,
                    "subtitle" => "Click the link below to check the payment history.",
                    "buttonText" => "Payment History",
                    "buttonLink" => env("APP_URL")."/history",
                    "message" => "$".$price." has been ".$status." with ".$paymentMode." by ".$payment_option." .<br> Thank you<br>".env("APP_NAME")." Team"
                ),
                "withbutton"
            );
        return $invoice_id;
    }

    public static function getSecurityPaid($student_id){
        $res=Database::table("invoices")->where("student_id", $student_id)->sum('security_paid','security_paid')[0]->security_paid;
        return $res==null?0:$res;
    }

    public static function getSecurityRefundByCash($student_id){
        $res=Database::table("invoices")->where("student_id", $student_id)->where("status", 'Refunded')->where("payment_mode", 'Cash')->sum('security_paid','security_paid')[0]->security_paid;
        return $res==null?0:$res;
    }

    public static function getSecurityRefundByCC($student_id){
        $res=Database::table("invoices")->where("student_id", $student_id)->where("status", 'Refunded')->where("payment_mode", 'Credit Card')->sum('security_paid','security_paid')[0]->security_paid;
        return $res==null?0:$res;
    }

    public static function getDrawerTotalByDate($open_batch){
        $total_transaction = 0;
        $invoices = Database::table("invoices")->where('company',$open_batch->company)->where("created_at",">=", $open_batch->start_time)->where("created_at","<=", date("Y-m-d H:i:s"))->get();
        if(!empty($invoices)){
            foreach($invoices as $each_invoice){
                if (InvoiceModel::IsBatch($each_invoice,$open_batch->user_id))
                    $total_transaction += $each_invoice->price-$each_invoice->security_paid;
            }
        }
        return $total_transaction;
    }

    public static function getDrawerTotalCredit($open_batch){
        $total_transaction = 0;
        $invoices = Database::table("invoices")->where('company',$open_batch->company)->where('payment_mode','Credit')->where("created_at",">=", $open_batch->start_time)->where("created_at","<=", date("Y-m-d H:i:s"))->get();
        if(!empty($invoices)){
            foreach($invoices as $each_invoice){
                if (InvoiceModel::IsBatch($each_invoice,$open_batch->user_id))
                    $total_transaction += $each_invoice->price;
            }
        }
        return $total_transaction;
    }

    public static function IsBatch($invoice,$owner_id){
        if((($invoice->payment_mode == "Cash") || ($invoice->payment_mode == "Check") || ( $invoice->payment_mode == "Credit Card")) && ($owner_id==1 || $owner_id==$invoice->paid_user_id)) {
            //do not include security deposit refunds or online credit card transactions.
//            if (env('SITE_Portal') && ($invoice->status == 'Refunded' || $invoice->payment_option!='by_admin'))
            if (env('SITE_Portal') && ($invoice->payment_option!='by_admin'))
                return false;
            $student=StudentModel::GetUser($invoice->student_id);
            if ($student->account_type=='Test')
                return false;
            return true;
        }
        return false;
    }
}