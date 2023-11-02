<?php

namespace Simcify\Models;

use Simcify\Database;
use Simcify\Auth;

class ActionModel
{
  public $user_id;

  public $action_id;

  public static function getAllActions()
  {
    $action_log = array();
        $t_action_log = Database::table("action_log")->company()->get();
        foreach($t_action_log as $each_action_log){
            $person = Database::table("users")->find($each_action_log->admin_id);
            $each_action_log->person = $person->fname . " " . $person->lname;
            $action_log[] = $each_action_log;
        }

        $data = array(
            "user" => Auth::user(),
            "action_log" => $action_log
        );

        return $data;
  }

  public static function getUserAction($user_id)
  {
    $action_log = array();
        
    $action_log = Database::table("action_log")->where("user_id", $user_id)->get();

      return $action_log;

  }
}