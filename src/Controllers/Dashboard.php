<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use Simcify\Models\InvoiceModel;
use Simcify\Models\OrderModel;
use Simcify\Models\StudentModel;
use Simcify\Models\RoomModel;


class Dashboard{

    /**
     * Get dashboard view
     * 
     * @return \Pecee\Http\Response
     */
    public function get() {
    	$user = Auth::user();
    	if ($user->role == "user") {
            $requestsData = Database::table("requests")->where("receiver", $user->id)->last();
//            $document_key=Database::table("files")->where("document_key" , $requestsData->document)->first()->document_key;
            $document_key=$requestsData->document;
    	    if ($user->sign_status==StudentModel::Sent){
                redirect(url("Guest@open").$requestsData->signing_key);
            }
    	    else {
                // invoice
                $invoice = Database::table("invoices")->where("student_id" , $user->id)->get();
                $user->phone_number = StudentModel::getPhoneNumber($user);
                $student=$user;
                $user->page_title="Student Profile";
                $enum = Database::table("users")->enum_values('sponsor');
                $user->notes=Database::table('notes')->where('student_id',$user->id)->where('type','external')->get();
                $phone_code = Database::table("country_phone")->where("iso3", "<>" , "NULL")->where("iso", "<>" , "US")->get();
                return view('dashboard', compact("user","student", "invoice","document_key","enum",'phone_code'));
            }
    	}
    	else{
        
            
            // admin dashboard   
	        $folders = Database::table("folders")->company()->count("id", "folders")[0]->folders;

	        // signed vs unsigned
	        $signed = Database::table("files")->company()->where("status" , "Signed")->count("id", "total")[0]->total;
	        $unsigned = Database::table("files")->company()->where("status" , "Unsigned")->count("id", "total")[0]->total;
	    	// pending signing requests 
	    	$pendingRequests = Database::table("requests")->company()->where("status" , "Pending")->count("id", "total")[0]->total;

            $buildings = RoomModel::GetBuildings();
            
            
            $companies = Database::table("companies")->get();

            $finance = $this->GetResidentsFinancial();
            
            return view('dashboard', compact("user","folders","pendingRequests","signed","unsigned","buildings","companies","finance"));
    	}
    }
    
    public function GetResidentsFinancial(){
        $db= Database::table("users")->where("status", '!=','Terminated')->where("role", "user")->where('action','!=', StudentModel::Deleted)->orderBy("id",false);
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

        $customers=$db->get();

        $owed=[];
        $holding=[];
        $num=[];
        foreach ($customers as $key => $residents) {
            $owed[$residents->company] += $residents->balance;
            $holding[$residents->company] += $residents->holding_balance;
            $num[$residents->company] += 1;
        };

        return ['owed' => $owed , 'holding' => $holding , 'num' => $num ];
        
    }
}
