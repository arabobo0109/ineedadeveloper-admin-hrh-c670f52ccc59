<?php
namespace Simcify\Models;
use Simcify\Auth;
use Simcify\Database;

class BalanceModel
{
    const Room='Room';
    const Security='Security';
    const Administration='Administration';
    const Laundry='Laundry';
    const Fine='Fine';
    const Other='Other';
    const Holding='Holding';
    const None='None';

    const Owed="Owed";
    const Paid="Paid";
    const Canceled="Canceled";
    const Refunded='Refunded';


    static public function addBalanceHistory($user, $amount, $action, $note,$invoice_id=0,$type=BalanceModel::None,$owed_balance=0){
        $balance_history = array(
            "amount" => $amount,
            "action" => $action,
            "note" => $note,
            "invoice_id" => $invoice_id,
            "type"=>$type
        );
        BalanceModel::insertBalance($user,$balance_history);
    }

    static public function insertOwed($user, $dd){
        $dd['paid_status']=BalanceModel::Owed;
        $dd['owed_amount']=$dd['amount'];
        BalanceModel::insertBalance($user,$dd);
    }

    static public function insertPaid($user, $dd,$owed_id,$paid_amount){
        $old=Database::table("balance_history")->find($owed_id);
        $ud=array('owed_amount'=>$old->owed_amount-$paid_amount);
        if ($ud['owed_amount']<=0)
            $ud['paid_status']=BalanceModel::Paid;
        Database::table("balance_history")->where('id',$owed_id)->update($ud);

        $dd['paid_status']=BalanceModel::Paid;
        $dd['owed_id']=$owed_id;
        $dd['amount']=-$paid_amount;
        $dd['type']=$old->type;
        BalanceModel::insertBalance($user,$dd);

        if ($ud['owed_amount']<0){
            ReportModel::SendError("owed_amount:".$ud['owed_amount'].' from user_id:'.$user->id);
        }

    }

    static public function insertBalance($user, $dd){
        if ($dd['action']=="Payment with Holding"){
            $dd['note']=$dd['note']." (Holding is $".$user->holding_balance.")";
        }
        else{
            $user->balance+=$dd['amount'];
            StudentModel::Update($user->id,array('balance' => $user->balance));
        }

        $dd['student_id']=$user->id;
        $dd['balance']=$user->balance;
        $dd['status']=$user->status;
        $dd['company']=$user->company;
        $by=Auth::user();
        if (!isset($by->role))
            $dd['made_by']='System';
        else if ($by->role=='user')
            $dd['made_by']='Student';
        else
            $dd['made_by']=$by->fname;
        Database::table("balance_history")->insert($dd);
    }

    static public function addHoldingBalance($user, $amount){
        $user->holding_balance+=$amount;
        StudentModel::Update($user->id,array('holding_balance' => $user->holding_balance));
    }

    static public function refund($item,$user){
        $ud=array('paid_status'=>self::Refunded);
        Database::table("balance_history")->where('id',$item->id)->update($ud);
        $ad = array(
            "amount" =>-$item->amount, // $item->amount<0
            "action" => "Refunded",
            "note" => $item->note."(Refunded)",
            "type"=>$item->type
        );

        if ($item->type==BalanceModel::Holding){
            if ($ad['amount']<=$user->holding_balance){
                BalanceModel::addHoldingBalance($user, -$ad['amount']);
                StudentModel::UpdateBalance($user,$ad['amount']);
                return;
            }
            else{
                $ad['amount']-=$user->holding_balance;
                StudentModel::UpdateBalance($user, $user->holding_balance);
                BalanceModel::addHoldingBalance($user, -$user->holding_balance);

            }
        }

        BalanceModel::insertOwed($user,$ad);
    }

    static public function Cancel($balance_id){
        $item=Database::table("balance_history")->find($balance_id);

        $ad = array(
            "amount" =>-$item->owed_amount,
            "action" => "Canceled",
            "note" => $item->note."(Canceled)",
            "type"=>$item->type,
            'owed_id'=>$balance_id,
            'paid_status'=>BalanceModel::Canceled
        );
        $user=StudentModel::GetUser($item->student_id);
        BalanceModel::insertBalance($user,$ad);

        $ud=array('owed_amount'=>0,'paid_status'=>BalanceModel::Canceled);
        Database::table("balance_history")->where('id',$balance_id)->update($ud);

    }

    static public function getOwed($user_id){
        return Database::table("balance_history")->where("student_id" , $user_id)->where("owed_amount" , '>',0)->get();
    }

    static public function getTotalOwed($user_id,$type=null){
        $db=Database::table("balance_history")->where("student_id" , $user_id)->where("owed_amount" , '>',0);
        if ($type!=null){
            $db=$db->where('type',$type);
        }
        $balance=$db->sum('owed_amount','balance');
        return $balance[0]->balance+0;
    }

    static public function getTodayRoomOwed($user_id){
        return Database::table("balance_history")->where("student_id" , $user_id)->where("type" , 'Room')->where("amount" , '>',0)->where("created_at",">=", date("Y-m-d"))->where("action",'Balance Owed')->get();
    }
}