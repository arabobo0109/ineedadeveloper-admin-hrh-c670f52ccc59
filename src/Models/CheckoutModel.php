<?php
namespace Simcify\Models;
use Simcify\Database;

class CheckoutModel
{
    static public function AdditionalPayment($user, $amount,$transaction,$card){  //not using
//        BalanceModel::addHoldingBalance($user, $amount);
//        $invoice_id=InvoiceModel::addInvoice($user, $amount, "Cash", "Paid", 0, $transaction, $card,0,0,0,0, $amount,"by_admin");
//
//        BalanceModel::addBalanceHistory($user,-$amount, "Payment with Cash", "Additional Payment for exceeded checkout",$invoice_id);
    }
}