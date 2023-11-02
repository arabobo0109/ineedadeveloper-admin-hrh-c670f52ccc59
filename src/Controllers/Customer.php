<?php
namespace Simcify\Controllers;

use Simcify\Date;
use Simcify\DocSign;
use Simcify\Models\CompanyModel;
use Simcify\Models\InvoiceModel;
use Simcify\Models\OrderModel;
use Simcify\Models\BalanceModel;
use Simcify\Models\ReportModel;
use Simcify\Models\RoomModel;
use Simcify\Models\StudentModel;
use Simcify\Nmi;
use Simcify\Str;
use Simcify\File;
use Simcify\Mail;
use Simcify\Auth;
use Simcify\Database;
use Simcify\Models\PaymentModel;
use function Simcify\print_r2;
use Simcify\Models\ActionModel;

class Customer extends Admin {
    public function get() {
        $db= Database::table("users")->company()->where("role", "user")->where('action','!=', StudentModel::Deleted)->orderBy("id",false);
        if (!empty(input("search"))) {
            $db = $db->where("fname","LIKE", "%".input("search")."%");
        }
        elseif(!empty(input("unit"))) {
            $unit=StudentModel::ReplaceUnit(input("unit"));
            $db = $db->where("unit","LIKE", "%".$unit."%");
        }
        elseif (isset($_GET['status'])) {
            $status=$_GET['status'];
            $db = $db->where("status", $status);
        }
        else{
            $db = $db->where("status", '!=','Terminated');
        }

        $customers=$db->get();
        $employers = CompanyModel::GetEmployers();
        $companies = CompanyModel::GetCompany();
        $company_security = $companies->security;
        $company_weekly = $companies->weekly;

        foreach ($customers as $customer) {
            $customer->employer_name='None';
            if ($customer->employer>0) {
                foreach ($employers as $employer)
                    if ($employer->id == $customer->employer) {
                        $customer->employer_name = $employer->name;
                        break;
                    }
            }
        }
        $user = Auth::user();
        $enum = Database::table("users")->enum_values('sponsor');
        $phone_code = Database::table("country_phone")->where("iso3", "<>" , "NULL")->where("iso", "<>" , "US")->get();

        $email_templates = Database::table("email_templates")->company()->get();
        return view('student_list', compact("user", "customers", "employers" ,"enum", "company_security", "company_weekly", "phone_code","email_templates"));
    }

    public function profile($user_id) {
        $user = Auth::user();
        $student = Database::table("users")->find($user_id);
        if (empty($student))
            return "no student";
        if ($student->company!=cid())
            return view('errors/404_permission');

        // invoice
        $invoice = Database::table("invoices")->where("student_id" , $student->id)->get();
        // get request
        $requestsData = Database::table("requests")->where("receiver", $student->id)->orderBy("id", false)->first();
        $document_key=Database::table("files")->where("document_key" , $requestsData->document)->first()->document_key;

        $balance_history = Database::table("balance_history")->where("student_id" , $student->id)->get();
        // Email Template
        $email_templates = Database::table("email_templates")->company()->get();

        $student_room_fee = PaymentModel::getStudentRoomFee($student);
        $student->weekly_rate = $student_room_fee['week_fee'];
        $student->phone_number = StudentModel::getPhoneNumber($student);
        $student->depositHeld = InvoiceModel::getSecurityPaid($student->id);
        $student->employer_name=StudentModel::getEmployerName($student);

        $batches = Database::table("drawer_batch")->company()->where("user_id", $user->id)->where("status", "open")->get();
        $student->drawer_number=count($batches)>0?$batches[0]->drawer_number:0;

        $phone_code = Database::table("country_phone")->where("iso3", "<>" , "NULL")->where("iso", "<>" , "US")->get();

        $user->page_title = "Student Profile";

        $enum = Database::table("users")->enum_values('sponsor');
        $user->notes=Database::table('notes')->where('student_id',$user_id)->get();
        $employers = CompanyModel::GetEmployers();

        $action_data = ActionModel::getUserAction($student->id);

        return view('customer/profile', compact("user", "student" ,"invoice", "document_key", "balance_history", "phone_code", "email_templates","enum",'employers','action_data'));
    }

    public function create() {
        header('Content-type: application/json');


        $user_data=StudentModel::CreateUser();

        foreach (input()->post as $field) {
            if ($field->index == "room_id" || $field->index == "csrf-token" || $field->index == "initial_security_fee" || $field->index == "auto_room" || $field->index == "print_lease") {
                continue;
            }
            $user_data[$field->index] = escape($field->value);
        }
        $user_data['birthday']=($user_data['birthday']=='')?'1800.01.01':$user_data['birthday'];

        if ($user_data['lease_end']=='' || $user_data['lease_end']==$user_data['lease_start'])
            $user_data['lease_end']=Date::GetLeaseEnd($user_data['lease_start']);
        $user_data['real_end']=$user_data['lease_end'];

        $lease_on_file = 0;
		$email = input('email');

    	if($email == ""){
            $email = $_POST["print_lease"];
            $user_data["email"] = $email;
            $user_data['sign_status'] = StudentModel::Lease_on_File;
            $lease_on_file = 1;
        }
        $signup = Auth::signup(
            $user_data,
            array(
                "uniqueEmail" => $email
            )
        );

        if ($signup["status"] == "success") {
            $signup_user = Database::table("users")->find($signup["user_id"]);
            $fees = CompanyModel::GetCompany();
            $fees->security=input('initial_security_fee');
            OrderModel::Add($signup_user,$fees);
            $this->createLMSUser($signup_user);

            // Action Log
            Customer::addActionLog("Student", "Create Student", input('fname') ." ". input('lname')." email:".$email,$signup_user->id);

            if (env("SITE_Portal"))
                Zk::addPerson($signup_user);

            $message="Please Assign a Bed.";
            if($email != ""){
                DocSign::sendAgreement($email,$signup_user->id,$lease_on_file);
                $message="The student was created and a lease agreement email was sent to ".$email.". ".$message;
            }
            exit(json_encode(responder("success", "Student Created", $message,"showRoomModal(".$signup["user_id"].")")));


        }else{
            exit(json_encode($signup));
        }
    }

    public function createLMSUser($user){
        return;
        $url = env('LMS_REGISTER_SITE_URL').'register/create_from_main';

        $post_query = "fname=".$user->fname."&";
        $post_query .= "lname=".$user->lname."&";
        $post_query .= "email=".$user->email."&";
        $post_query .= "mobile=".$user->phone."";
        $url .= "?". $post_query;

        $ch=curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_query);
        curl_setopt($ch, CURLOPT_POST, 1);


        curl_exec($ch);
        $err = curl_error($ch);

        curl_close($ch);
        if ($err)
            echo "cURL Error #:" . $err;

    }
    
    public function create_employer() {        
        $employer_data = array(
            "name" => $_POST["name"],
            "company_info" => $_POST["company_info"],
            "email" => $_POST["email"],
            'company' => Auth::cid()
        );
		
		Database::table("employers")->insert($employer_data);
        $return_employers = CompanyModel::GetEmployers();
        $return_options = '<option value="0"></option>';
        foreach($return_employers as $return_employer){
            $return_options = $return_options.'<option value="'.$return_employer->id.'">'.$return_employer->name.'</option>';
        }
        $return_options = $return_options.'<option value="other">Other</option>';
        
        echo $return_options;
    }

    public function uploadLease($user_id) {
        $student = Database::table("users")->find($user_id);
        $user = Auth::user();
        $user->page_title='Upload Lease Document';
        return view('customer/upload_lease', compact("user", "student"));
    }

    public function updateUploadLease(){
        try{
            header('Content-type: application/json');

            if($_FILES['file']['name'] == ""){
                exit(json_encode(responder("error", "No File", "Please select file to upload.", "reload()")));
            }
            else{
                $targetPath = getcwd() .'/uploads/lease/' . $_FILES['file']['name'];
                move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

                StudentModel::Update(input("select_user"),array('lease_upload' => $_FILES['file']['name']));


                exit(json_encode(responder("success", "Uploaded", "Lease Document uploaded successfully.", "reload()")));
            }

        }
        catch(Exception $e) {
            echo '\nCaught exception: ',  $e->getMessage(), "\n";
        }
    }

    public function changeStatus() {
        $user = Database::table("users")->find(input("customerid"));
        $status=input('status');
        if ($status==StudentModel::Suspended){
            StudentModel::delete($user,StudentModel::Suspended);
        }
        else
            StudentModel::Update($user->id,array('status'=>$status,'action'=>StudentModel::Active));

        if ($status==StudentModel::Paused)
            StudentModel::Update($user->id,array('checked_out_at'=>date("Y-m-d H:i:s")));

        Customer::addActionLog("Student", $status." Student", $user->fname ." ". $user->lname." ".$user->email,$user->id);

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Status is ".$status, "The student was successfully changed.","reload()")));
    }

    public function delete() {
        $account = Database::table("users")->find(input("customerid"));
        StudentModel::Remove($account);
        // Action Log
        Customer::addActionLog("Student", "Delete Student", $account->fname ." ". $account->lname." ".$account->email,$account->id);
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Student Deleted!", "The student was successfully deleted.","reload()")));
    }

    public function makeTester() {
        StudentModel::Update(input("customerid"),array('account_type'=>'Test'));
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Tester Student!", "The student is tester now.","reload()")));
    }

    public function assign_room() {
        $data = array(
            "user" => Database::table("users")->find(input("customerid")),
            "buildings" => RoomModel::GetBuildings()
        );
        return view('extras/assign_room', $data);
    }

    public function updateSpecificWeeklyRate(){
        header('Content-type: application/json');
        $newWeekly=input("weekly");
        $user= Database::table("users")->find(input("customerid"));
        Database::table("users")->where("id" , $user->id)->update(array('weekly_rate' => $newWeekly));

        if($user->is_subscribe == 1){
            $gw = new Nmi();
            $gw->setLogin();
            $default_plan_db = Database::table("nmi_plan")->where("plan_id" , env("NMI_PLAN_DEFAULT_ID"))->first();
            $dafault_plan_id = $default_plan_db->id;
            if($user->nmi_plan_id == $dafault_plan_id){
                $plan_id = env("NMI_PLAN_PREFIX")."_".$user->id."_plan";
                $plan_name = "Weekly Room Rate Subscription for ". $user->fname;

                $gw->deleteSubscription($user->subscription_id);
                $response = $gw->responses;
                if($response['response'] == 1){
                    $s = array(
                        "subscription_id" => "",
                        "nmi_plan_id" => 0,
                    );
                    StudentModel::Update($user->id, $s);
                    $result="Subscription Success Canceled!";
                }
                else {
                    exit(json_encode(responder("error", "NMI Error.",$response['responsetext'])));
                }

                $gw->addPlan($newWeekly,$plan_id, $plan_name);
                $response = $gw->responses;
                if($response['response'] == 1){
                    $n = array(
                        "plan_id" => $plan_id,
                        "amount" => $newWeekly,
                    );
                    Database::table("nmi_plan")->insert($n);
                }
                else {
                    exit(json_encode(responder("error", "NMI Error.",$response['responsetext'])));
                }
            }
        }

        $feeList=BalanceModel::getTodayRoomOwed($user->id);
        foreach ($feeList as $fee){
            if ($fee->paid_status=='None'){  //old payment  // there is not result
            }
            $diffRate = $newWeekly - $fee->amount;
            $balance_history = array(
                "amount" =>$diffRate,
                "action" => "Specific Weekly Rate",
                "note" => "Changed room fee from $".$fee->amount." to $".$newWeekly,
                "type"=>BalanceModel::Room
            );
            if ($diffRate>0){  // 10=110-100
                BalanceModel::insertOwed($user,$balance_history);
            }
            else{  // -10=90-100

                BalanceModel::insertPaid($user,$balance_history,$fee->id,-$diffRate);
            }
        }

        $message="Changed Specific Weekly Rate of ". $user->fname ." ". $user->lname." new rate:".$newWeekly;
        Customer::addActionLog("Profile", "Edit Weekly Rate",$message,$user->id );
//        ReportModel::SendError($message);
        Mail::send(
            $user->email, "Weekly Rate",
            array(
                "message" => "Your Weekly rate was changed by admin to $". $newWeekly ." .<br> Thank you<br>"
            )
        );

        exit(json_encode(responder("success", "Saved.","Specific Weekly Rate has changed","reload()")));
    }

    public function updateLeaseStartDate(){
        header('Content-type: application/json');
        $user=Database::table("users")->find(input("customerid"));
        OrderModel::ChangeOrderDate($user,input("lease_start"));
        exit(json_encode(responder("success", "Changed Date.","Lease Start date has changed","reload()")));
    }

    public function addAmountToBalance(){
        $student = Database::table("users")->find(input("payment_user_id"));
        $balance =  input("price_total");
        $type=input('type','Other');

        $dd = array(
            "amount" =>$balance,
            "action" => "Add Amount",
            "note" => $type.' ('.input("note_to_balance").')',
            "type"=>$type
        );
        BalanceModel::insertOwed($student,$dd);
        // Action Log
        Customer::addActionLog("Profile", "Add Amount To Student Balance",  $student->fname ." ". $student->lname." as $".$balance,$student->id);

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Added to Balance.","The amount $". $balance ." added to student balance successfully.","reload()")));
    }

    public static function addActionLog($action_type, $action_sub_type, $action_content,$user_id=0){
        $action_log = array(
            "admin_id" => Auth::id(),
            "action_type" => $action_type,
            "action_sub_type" => $action_sub_type,
            "action_content" => $action_content,
            'company'=>Auth::cid(),
            'user_id'=>$user_id
        );
        Database::table("action_log")->insert($action_log);
    }

    public function updateRoom() {
        $bed_id=input("bed_id");
        $room_id=input("room_id");
        $user= Database::table("users")->find(input("user_id"));

        RoomModel::SetVacant($user->bed_id);  //previous bed should be Vacant status.
        Database::table("beds")->where("id" , $bed_id)->update(array('student_id' => $user->id,'status'=>RoomModel::Occupied));

        $roomName0=Database::table("rooms")->find($room_id)->name;
        $room_name=$roomName0.'-'.Database::table("beds")->find($bed_id)->name;
        $special_room = Database::table("special_room")->where("bed_id", $bed_id)->first();
        if(!empty($special_room)){
            ReportModel::SendError("Special Room Assigned User_id:".$user->id." bed_id:".$bed_id);
        }
        StudentModel::Update(input("user_id"),array('bed_id' => $bed_id,'room_id'=>$room_id,'unit'=>$room_name,'created_at'=>date("Y-m-d H:i:s")));
        $message="Room ".$room_name." was assigned to the student : ". $user->fname ." ". $user->lname;

        if (env("SITE_Portal")){
            $zk_message=Zk::AddCard($user,input('card_number'),$roomName0);
            $message.=$zk_message;
        }

        // Action Log
        Customer::addActionLog("Room", "Room Assignment", $message,$user->id);
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Room Assignment",$message,"reload()")));
    }

    public function cancel_lease() {
        $data = array(
            "user" => Database::table("users")->find(input("customerid")),
            "buildings" => RoomModel::GetBuildings(),
            "cancel_lease_items" => Database::table("cancel_lease_item")->get()
        );
        return view('extras/cancel_lease', $data);
    }

    public function updateCancelLease() {
        $user = Database::table("users")->find(input("user_id"));
        $cancel_lease_ids_get = input("cancel_lease_item_ids");

		$cancel_lease_ids = array();
        foreach($cancel_lease_ids_get as $cancel_lease_id){
        	$cancel_lease_ids[] = $cancel_lease_id->value;
		}
		$ad = array(
            "student_id" => $user->id,
            "cancel_lease_ids" => json_encode($cancel_lease_ids),
            'company' => $user->company
        );
        header('Content-type: application/json');

        Database::table("cancel_lease_history")->insert($ad);
        RoomModel::SetVacant($user->bed_id);

        StudentModel::SetTerminated($user,StudentModel::Canceled);
        BalanceModel::addBalanceHistory($user,-($user->balance), "Cancel Lease", "Balance returned",0,BalanceModel::None);

        // Action Log
        $message=$user->fname ." ". $user->lname."'s lease was canceled.";
        Customer::addActionLog("Student", "Cancel Lease", $message,$user->id);
        exit(json_encode(responder("success", "Cancel Lease" ,$message,"reload()")));
    }

    public function add_fine() {
        $student = Database::table("users")->find(input("customerid"));
        $room_name = Database::table("rooms")->find($student->room_id)->name.'-'.Database::table("beds")->find($student->room_id)->name;
        $record_person = array();
        $fine_fees = Database::table("fine_fees")->company()->get();

        $data = array(
            "user" => $student,
            "buildings" => RoomModel::GetBuildings(),
            "fine_fees" => $fine_fees,
            "record_person" => $record_person,
            "room_name" => $room_name
        );

        return view('extras/add_fine', $data);
    }

    public function resendDocusign() {
        DocSign::sendAgreement(input("email"),input('user_id'));
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Docusign Email Sent!", "Docusign Email was sent.","reload()")));
    }

    public function sendCheckin() {
        $email=input("email");
        $reminder = Database::table("reminders")->company()->first();

        $token = Str::random(32);
        $data = array('token' => $token);
        Database::table('users')->where("email", $email)->update($data);
        $link = env("APP_URL")."/checkin?token=".$token;

        Mail::send(
            $email, $reminder->subject,
            array(
                "title" => "Check-in reminder",
                "subtitle" => "Click the link below to check-in.",
                "buttonText" => "Check-in now",
                "buttonLink" => $link,
                "message" => $reminder->message
            ),
            "withbutton"
        );
//
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Email Sent!", "Checkin link email sent.","reload()")));
    }

    public function set_arrived() {
        $user=Database::table("users")->find(input("user_id"));
        OrderModel::ChangeOrderDate($user,date('Y-m-d'));
        Customer::addActionLog("Student", "Arrived", $user->fname,$user->id);

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Status was changed to Arrived!", "Lease was started.","reload()")));
    }

    public function violation() {
        StudentModel::Update(input("user_id"),array('flag' => 'Violation'));
        StudentModel::AddNote(Auth::user(),input("user_id"),'internal','Set Violation');

        $message=" ";
        if (env('SITE_Portal')){
            $user = Database::table("users")->find(input("user_id"));
            $message=Zk::DisableCard($user);
        }

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Violation flag.", "Violation flag was put.".$message,"reload()")));
    }

    public function employer_paid() {
        $user=Database::table("users")->find(input("user_id"));
        StudentModel::Update(input("user_id"),array('paid_employer_id' =>$user->employer));
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Employer Payment.", "Employer will make all payment.","reload()")));
    }

    public function comeback() {
        $user=Database::table("users")->find(input("user_id"));

        header('Content-type: application/json');
//        if ($user->flag=='Violation')
//            exit(json_encode(responder("error", "Oops!", "This student was flagged as Violation.")));

        $fees = CompanyModel::GetCompany();

        if ($user->status==StudentModel::Paused){
            $ud=array('status'=>StudentModel::Arrived,
               'lease_start'=> date("Y-m-d"),
               'lease_end'=>Date::GetLeaseEnd($user->lease_start),
                'weekly_rate'=>$fees->weekly
            );

            StudentModel::Update($user->id,$ud);
            exit(json_encode(responder("success", "Changed!", 'This student status was changed.',"reload()")));

        }

        $user->email=str_replace('T'.$user->id.'_','',$user->email);
        $user->lease_start=date("Y-m-d");
        $user->lease_end=Date::GetLeaseEnd($user->lease_start);
        $user->real_end=$user->lease_end;
        $user->weekly_rate=$fees->weekly;
        $user_data = json_decode(json_encode($user), true);
        unset(
            $user_data['id'],
            $user_data['created_at'],
            $user_data['lastnotification'],
            $user_data['room_id'],$user_data['bed_id'],$user_data['unit'],$user_data['balance'],$user_data['holding_balance'],$user_data['outstanding_balance'],$user_data['paid_employer_id'],$user_data['security_deposit'],
            $user_data['lease_upload'],
            $user_data['subscription_id'],
            $user_data['nmi_plan_id'],
            $user_data['is_subscribe'],
            $user_data['status'],
            $user_data['sign_status'],
            $user_data['action'],
            $user_data['checked_out_at'],
            $user_data['flag']
        );
        $signup = Auth::signup(
            $user_data,
            array(
                "uniqueEmail" => $user->email
            )
        );

        if ($signup["status"] == "success") {
            $signup_user = Database::table("users")->find($signup["user_id"]);
            OrderModel::Add($signup_user,$fees);
            $this->createLMSUser($signup_user);

            // Action Log
            Customer::addActionLog("Student", "Comeback Student", " email:".$user->email,$signup_user->id);

            if (env("SITE_Portal"))
                Zk::addPerson($signup_user);
            DocSign::sendAgreement($user->email,$signup_user->id);
            $message="A new profile was created and a lease agreement email was sent to ".$user->email.".";
            $url = url("Customer@profile").$signup_user->id;
            exit(json_encode(responder("success", "New profile", $message,"redirect('".$url."')")));
        }else{
            exit(json_encode($signup));
        }
    }
}
