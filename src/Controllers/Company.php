<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use Simcify\Models\CompanyModel;
use Simcify\Models\StudentModel;

class Company{
    public function go($cid) {
        $user=Auth::user();
        if ($user->role != "superadmin") {
            return view('errors/404_permission.php');
        }
        $user->company=$cid;
        Auth::authenticate($user);
        StudentModel::Update($user->id,array('company'=>$cid));
        redirect(url("Company@get"));
    }

    public function get() {
    	$data = array(
    			"user" => Auth::user(),
    			"companies" => Database::table("companies")->get()
    		);
        return view('companies', $data);
    }

    /**
     * Create business user account
     * 
     * @return Json
     */

    public function create() {
        header('Content-type: application/json');
        $company_data = array(
            "name" => $_POST["name"],
            "address" => $_POST["address"],
        );

        Database::table("companies")->insert($company_data);

        exit(json_encode(responder("success","Alright", "Sponsor successfully created","reload()")));
    }

    public function delete() {
        Database::table("companies")->where("id", input("companyid"))->delete();

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Sponsor Deleted!", "Sponsor successfully deleted.","reload()")));
    }

    public function updateview() {
        $data = array(
                "company" => CompanyModel::GetCompany()
            );
        return view('extras/update_company', $data);
    }

    public function update() {
    	
        foreach (input()->post as $field) {
            if ($field->index == "csrf-token" || $field->index == "companyid") {
                continue;
            }
            Database::table("companies")->where("id" , input("companyid"))->update(array($field->index => escape($field->value)));
        }

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Alright!", "Employer was successfully updated","reload()")));
    }

    public function slugify($text, string $divider = '-')
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

}
