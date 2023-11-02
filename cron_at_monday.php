<?php

include_once 'vendor/autoload.php';

use Simcify\Database;
use Simcify\Auth;
use Simcify\Application;
use Simcify\Mail;
use Simcify\Models\StudentModel;

$app = new Application();
//WI
//0 4 * * MON cd /var/www/irhliving && php cron_at_monday.php company_id=1
//TN
//0 3 * * MON cd /var/www/irhliving && php cron_at_monday.php company_id=2
//50 3 * * MON cd /var/www/irhliving && php cron_at_monday.php company_id=3
//http://amg.signer/cron_at_monday.php

parse_str(implode('&', array_slice($argv, 1)), $_GET);
Auth::setCid($_GET['company_id']);

$today = date("Y-m-d");

//$students = Database::table("users")->where("created_at",">=", $today." 00:00:00")->where("created_at","<=", $today." 23:59:59.999")->get();

$today = date("Y-m-d");
$filename = $today.' '.env('Site_Name').'.xlsx';
$targetPath = getcwd() .'/uploads/excel/' . $filename;
$res=StudentModel::GetExcel(StudentModel::$fields_monday,$targetPath);

$emails = Database::table("monday_emails")->company()->get();
//push a email object to emails list
$emails[] = (object)['email' => env('Mail_Monday')];
$emails[] = (object)['email' => 'muellerUnity@gmail.com'];

$message="Hello there,<br><br>Here is a attachment excel file from <strong>".env("Site_Name")."</strong><br>
 Total count is <strong>".$res['total']."</strong>. 
 <br><br>";
//check TN site and add employer county
if (Auth::cid()==2){
    foreach (StudentModel::$mondayEmployers as $employer){
        $message.="Total count employed by ".$employer['name'].": <strong>".$employer['count']."</strong>.<br>";
    }
}

    $message.="<br><br>
 Thank you
 <br>".env("Site_Name")." Team";


foreach ($emails as $email){
    Mail::send(
        $email->email,
        "Weekly Student Data",
        array(
            "message" => $message
        ),
        "basic",
        null,
        array($filename => config("app.storage")."excel/".$filename)
    );
}

?>