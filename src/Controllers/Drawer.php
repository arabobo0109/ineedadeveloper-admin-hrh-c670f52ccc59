<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use Simcify\Models\DrawerModel;
use Simcify\Models\InvoiceModel;
use Simcify\Models\StudentModel;
use function Simcify\print_r2;

class Drawer extends Admin {
    public function get() {
        $user=Auth::user();
    	$batches = Database::table("drawer_batch")->company()->orderBy("id",false)->get();
        foreach ($batches as $batch){
            $owner=Database::table('users')->find($batch->user_id);
            $batch->owner=$owner->fname;
            if ($batch->user_id==$user->id && $batch->status=='open')
                $user->old_batch=$batch->drawer_number;
        }
    	$data = array(
    		"user" => $user,
            "batchs" => $batches,
            'terminals'=>Database::table("terminals")->company()->get()
        );
        return view('drawer_batch', $data);
    }

    public function viewTransaction($drawer_id){  //currently used
        $batch = Database::table("drawer_batch")->find($drawer_id);
        $owner=Database::table('users')->find($batch->user_id);
        $batch->owner=$owner->fname;

        $start_time = $batch->start_time;
        $end_time = $batch->end_time;
        if($batch->status == "open")
            $end_time = date("Y-m-d", strtotime("+1 day"));

        $invoices = Database::table("invoices")->innerJoin('users','invoices.student_id','users.id')
            ->where("users`.`company",Auth::cid())
            ->where("invoices`.`created_at",">=", $start_time)->where("invoices`.`created_at","<=", $end_time)->get('payment_mode','unit','price','security_paid','room_paid','administration_paid','laundry_paid','holding_paid','fine_paid','`invoices.created_at`','unit','student_id','name','transaction_id','payment_option','`invoices.status`','`invoices.paid_user_id`');

        $total = array(
            "cash" => 0,
            "credit" => 0,
            "check" => 0,
            "security" => 0,
            'security_cc'=>0,
            'security_refunded'=>0
        );

        if(!empty($invoices)){
            $r_invoices = array();
            foreach($invoices as $invoice){
                if (InvoiceModel::IsBatch($invoice,$batch->user_id)){
                    $r_invoices[] =$invoice;
                    if($invoice->payment_mode == "Cash"){
                        $total['cash'] += $invoice->price-$invoice->security_paid;
                        $total['security_cash'] += $invoice->security_paid;

                        if($invoice->status == "Refunded")
                            $total['security_cash_refunded'] += $invoice->security_paid;
                        else
                            $total['security_cash_received'] += $invoice->security_paid;
                    }
                    else if($invoice->payment_mode == "Check")
                        $total['check'] += $invoice->price;
                    else if($invoice->payment_mode == "Credit Card"){
                        $total['credit'] += $invoice->price-$invoice->security_paid;
                        $total['security_cc'] += $invoice->security_paid;

                        if($invoice->status == "Refunded")
                            $total['security_cc_refunded'] += $invoice->security_paid;
                        else
                            $total['security_cc_received'] += $invoice->security_paid;
                    }
                    if($invoice->status == "Refunded")
                        $total['security_refunded'] += $invoice->security_paid;
                    else
                        $total['security_received'] += $invoice->security_paid;
                    $total['security'] += $invoice->security_paid;
                }
            }
            $invoices = $r_invoices;
        }

        $data = array(
            "user" => Auth::user(),
            "batch" => $batch,
            "start_time" => $start_time,
            "end_time" => $end_time,
            "invoices" => $invoices,
            "total" => $total
        );
//        print_r2($data);
        return view('extras/view_transaction', $data);
    }

    /**
     * Create business user account
     * 
     * @return Json
     */

    public function create() {
        $user = Auth::user();
        $drawer_number=DrawerModel::CreateBatch($_POST["open_amount"],$user->id);
        StudentModel::Update($user->id,array('season'=>input('season')));
        header('Content-type: application/json');
        Customer::addActionLog("Drawer", "Create Drawer", "Created new drawer: ". $drawer_number);
        exit(json_encode(responder("success","Alright", "new batch ". $drawer_number." was created","reload()")));
    }

    public function close() {
        $open_batch = Database::table("drawer_batch")->find(input("batch_id"));
        $drawer_number=DrawerModel::CloseBatch($open_batch,input("closing_amount"));
        header('Content-type: application/json');

        Customer::addActionLog("Drawer", "Close Drawer", "Closed drawer: ". $drawer_number);
        exit(json_encode(responder("success","Alright", "the batch ". $drawer_number." was closed","reload()")));
    }

    public function delete() {
        // Action Log
        $drawer = Database::table("drawer_batch")->find(input("batchid"));
        Customer::addActionLog("Drawer", "Delete Drawer", "Deleted drawer: Number-". $drawer->drawer_number);

        Database::table("drawer_batch")->where("id", input("batchid"))->delete();

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Batch Deleted!", "Batch successfully deleted.","reload()")));
    }

    public function closeview() {
        $data = array(
                "batch" => Database::table("drawer_batch")->where("id", input("batchid"))->first()
            );
        return view('extras/close_batch', $data);
    }

    public function insertCloseAmount() {
        header('Content-type: application/json');
        $open_batch = Database::table("drawer_batch")->company()->where("status", "open")->last();
        DrawerModel::AddCloseAmount($open_batch->id,input("closing_amount"));
        // Action Log
        Customer::addActionLog("Drawer", "Close Drawer", "Closing amount ".input("closing_amount")." was added in Drawer: Number-". $open_batch->drawer_number);

        exit(json_encode(responder("success", "Alright!", "Closing amount ".input("closing_amount")." was added.","reload()")));
    }
}
