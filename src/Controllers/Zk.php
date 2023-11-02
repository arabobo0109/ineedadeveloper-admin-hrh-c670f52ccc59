<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use Simcify\Models\StudentModel;

define("ZK_Log",$_SERVER['DOCUMENT_ROOT']."/log/zk.log");

class Zk extends Admin{
    public function index($user_id) {
        $student= StudentModel::GetUser($user_id);
        $data=Zk::getPerson($student);
        if (input('cmd')=='disable_key'){
            $data['cardNo'] = '';
        }
        else if (input('cmd')=='Enable'){
            $data['isDisabled'] = true;
        }
        else if (input('cmd')=='Disable'){
            $data['isDisabled'] = false;
        }
        if (input('cmd')){
            $res=Zk::curl("person/add",$data);
        }
        $student->cardNo=$data['cardNo']?$data['cardNo']:"Disabled";
        $student->zk_status=$data['isDisabled']?'Disable':'Enable';

        $user = Auth::user();
        $user->page_title='Door Access';
        return view('customer/zk_index', compact("user", "student"));
    }

    public function AssignCard(){
        $student = Database::table("users")->find(input('user_id'));
        $roomName0=Database::table("rooms")->find($student->room_id)->name;
        $message=Zk::AddCard($student,input('card_number'),$roomName0);
        header('Content-type: application/json');
        if (strpos($message,'New Card'))
            exit(json_encode(responder("success", 'Card Assign',$message,"reload()")));
        else
            exit(json_encode(responder("error", 'Card Assign',$message,"reload()")));
    }

    static public function AddCard($student,$card,$room){
        $data=Zk::getPerson($student);
        //SITE_Portal=1 in TN, SDC in SDC
        $level=env("SITE_Portal")=='1'?$room:env("SITE_Portal")."-".$room;

        $zkRes=ZK::curl("accLevel/getByName/".$level);
        $res=$zkRes['data'];
        $message=". There is no level.";
        if ($res!=null){
            $data['accLevelIds']=$res['id'];  //add level
            if (env("SITE_Portal")=='SDC'){
                $SDC_Inner_Ent='8acc83fd874f4dcf0188daf6624851a7';
                $data['accLevelIds'].=','.$SDC_Inner_Ent;
            }
            $message=". Level ".$level." was assigned.";
        }
        if ($card!='')
            $data['cardNo'] = $card;
        $res=Zk::curl("person/add",$data); //add card and level
        if ($res['code']==0) //success
            if ($card!='')
                $message=" New Card ".$card." was assigned".$message;
        else //failed
            $message=$res['message'].$message;
        return $message;
    }

    static public function DisableCard($student){
        $data=Zk::getPerson($student);
        $data['cardNo'] = '';
        $res=Zk::curl("person/add",$data);
        if ($res['code']==0) //success
            $message="The key card was removed";
        else //failed
            $message="The key card wasn't removed.". $res['message'];
        return $message;
    }

    static public function getPerson($student){
        $zkRes=ZK::curl("person/get/".ZK::getPersonalId($student->id));
        if ($zkRes['message']=="Person does not exist"){
            Zk::addPerson($student);
            return Zk::getPerson($student);
        }
        elseif ($zkRes['message']="success"){
            return $zkRes['data'];
        }
        return $zkRes['message'];
    }

    static public function getPersonalId($pin){
        return ($pin>5000 && $pin<5751)?$pin-5000:$pin;
    }

    static public function addPerson($student){
        $post = [
//            "accEndTime" => date('Y-m-d H:i:s', strtotime($student->lease_end)),
//            "accLevelIds" => "1",
//            "accStartTime" => date('Y-m-d H:i:s', strtotime($student->lease_start)),
            "email" => $student->email,
            "gender" => $student->gender=="Male"?"M":"F",
            "hireDate" => date('Y-m-d'),
            "lastName" => $student->lname,
            "mobilePhone" => $student->phone,
            "name" => $student->fname,
            "pin" => ZK::getPersonalId($student->id),
        ];
        Zk::curl("person/add",$post);
    }

    static public function delete_person($id){
        ZK::curl("person/delete/".ZK::getPersonalId($id),[]);
    }

    static public function curl($url,$data="Get"){
        if (env('app_url')=='http://amg.signer')
            error_log("\n".$url."   ".date("Y-m-d H:i:s")."\n", 3,ZK_Log);
        $url = env("ZK_URL") . "api/".$url."?access_token=" . env("ZK_ACCESS_TOKEN");
        $ch=curl_init($url);
        if ($data!="Get"){   //POST request
            if (env('app_url')=='http://amg.signer')
                error_log(print_r($data,true), 3,ZK_Log);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $result = curl_exec($ch);
        curl_close($ch);
        $result=json_decode($result,true);  //covert to Array  when true
        if (env('app_url')=='http://amg.signer')
            error_log(print_r($result,true), 3,ZK_Log);
        return $result;
    }
}
