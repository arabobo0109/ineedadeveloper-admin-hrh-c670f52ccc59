<?php
namespace Simcify\Models;
use Simcify\Auth;
use Simcify\Database;

class CompanyModel
{
    public static function GetCompany()
    {
        return Database::table("companies")->find(Auth::cid());
    }

    public static function SetCompany($ad)
    {
        $ad['company']=Auth::cid();
        return $ad;
    }

    public static function GetEmployers()
    {
        return Database::table("employers")->company()->where('status','Active')->orderBy('name', 'asc')->get();
    }
}