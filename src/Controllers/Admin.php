<?php
namespace Simcify\Controllers;

use Simcify\Auth;


class Admin{
    public $authUser;
    public function __construct() {
        $this->authUser = Auth::user();
        if (!isset($this->authUser->role) || $this->authUser->role == "user") {
            return view('errors/404_permission');
        }
    }
}
