<?php
namespace Simcify\Controllers;

use Simcify\Auth as Authenticate;
use Simcify\Database;
use Simcify\Models\StudentModel;
use Simcify\Mail;

class Auth{

    /**
     * Get Auth view
     * 
     * @return \Pecee\Http\Response
     */
    public function get() {
        $enabledguest = $guest = $signingLink = false;
        if (isset($_COOKIE['guest'])) {
            $guest = true;
            $guestData = unserialize($_COOKIE['guest']);
            $signingLink = url("Guest@open").$guestData[0]."?signingKey=".$guestData[1];
            $enabledguest = true;
            setcookie("guest", null, time()-30000, '/');
        }
        if (!isset($_GET['secure'])) {
            redirect(url("Auth@get")."?secure=true");
        }
        return view('login', compact("guest","signingLink"));
    }

    /**
     * Sign In a user
     * 
     * @return Json
     */
    public function signin() {
        $signIn = Authenticate::login(
		    input('email'), 
		    input('password'), 
		    array(
		        "rememberme" => true,
		        "redirect" => url(""),
		        "status" => "Suspended"
		    )
		);

        header('Content-type: application/json');
		exit(json_encode($signIn));
    }

    /**
     * Forgot password - send reset password email
     * 
     * @return Json
     */
    public function forgot() {
        $forgot = Authenticate::forgot(
		    input('email'), 
		    env('APP_URL')."/reset/[token]"
		);
        header('Content-type: application/json');
		exit(json_encode($forgot));
    }

    /**
     * Get reset password view
     * 
     * @return \Pecee\Http\Response
     */
    public function getreset($token) {
        return view('reset', array("token" => $token));
    }

    /**
     * Reset password
     *
     * @return Json
     */
    public function reset() {
        if (input('user_id')){   //create account with new password
            $user_id=input('user_id');
            $user = Database::table("users")->find($user_id);
            StudentModel::Update($user_id,
                array("password" => Authenticate::password(input("password")))
            );
            Authenticate::authenticate($user);
            $signingLink = env("APP_URL")."/view/".input('signingKey');

            $response = array(
                "status" => "success",
                "title" => "Your account was created!",
                "message" => "Please sign lease agreement.",
                "notify" => true,
                "callback" => "redirect('".$signingLink."', true);"
            );

            header('Content-type: application/json');
            exit(json_encode($response));

        }
        else{   //reset password
            $reset = Authenticate::reset(
                input('token'),
                input('password')
            );

            header('Content-type: application/json');
            exit(json_encode($reset));
        }
    }

    /**
     * Sign Out a logged in user
     *
     */
    public function signout() {
        Authenticate::deauthenticate();
        redirect(url("Auth@get"));
    }
}
