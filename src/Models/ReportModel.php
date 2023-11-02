<?php
namespace Simcify\Models;
use Simcify\Auth;
use Simcify\Database;
use Simcify\Mail;

class ReportModel
{
    const Init='init';
    const Active='active';
    const Closed='closed';

    public function __construct() {
    }

    static public function IsSpecificDate($operator)
    {
        return ($operator == "Today") || ($operator == "Yesterday") || ($operator == "This month") || ($operator == "Previous month");
    }

    static public function SendError($message)
    {
        Mail::send(
            env("DrawerReportMail3"), env('Site_Name')." id:".Auth::user()->id,
            array(
                "message" =>$message
            )
        );
    }
}