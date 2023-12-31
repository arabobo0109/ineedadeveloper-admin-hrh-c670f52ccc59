<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use Simcify\Models\InvoiceModel;
use Simcify\Models\OrderModel;
use Simcify\Models\PaymentModel;
use Simcify\Models\StudentModel;

class PrintController extends Admin{

    public function idPrint($user_id)
    {
        $user = Auth::user();
        $student = Database::table("users")->find($user_id);
        $user->page_title='ID Print';
        //it through a special format in the file name (see http://goo.gl/upaazT) so the
        $file_name ="ID_".$student->id."_".$student->fname."-PW=2.13-PH=3.38-PO=P";
        return view('customer/id_print', compact("user", "student","file_name"));
    }

    public function proofOfAddress($user_id)
    {
        $user = Auth::user();
        $student = Database::table("users")->find($user_id);
        $student->phone_number = StudentModel::getPhoneNumber($student);
        $user->page_title='Proof of Address Document';
        $file_name ="Address_".$student->id."_".$student->fname."-PW=8.3-PH=11.7";
        return view('customer/proof_address', compact("user", "student","file_name"));
    }

    public function invoicePrint($invoice_id)
    {
        $user = Auth::user();
        $invoice = Database::table("invoices")->find($invoice_id);
        $student = Database::table("users")->find($invoice->student_id);
        $extensionDate=date('Y-m-d', strtotime($student->real_end));
        $file_name ="Receipt_".$student->id."_".$student->fname."-PW=2.9-PH=6";
        return view('customer/receipt_print', compact("user", "student", "invoice","file_name","extensionDate"));
    }
}
