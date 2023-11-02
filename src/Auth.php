<?php
namespace Simcify;

use Exception;
use Simcify\Models\CompanyModel;
use Simcify\Str;

require("chat_conection.php");

class Auth {
    
    /**
     * Authenicate a user
     * 
     * @param   \Std    $user
     * @param   boolean $remember
     * @return  void
     */
    public static function authenticate($user, $remember = false) {
        session('signedUserId', $user->id);
        session('companySession', $user->company);
//        if( $remember && isset($user->remember_token) ) {
//            cookie('cmVtZW1iZXI', $user->remember_token, 30);
//        }
        ch_add_user_and_login($user);

    }

    public static function cb_auth(){
        $user=Auth::user();
        if (!empty($user))
            ch_add_user_and_login($user);
    }
    
    /**
     * Check if the user is authenticated
     * 
     * @return  void
     */
    public static function check() {
        return session()->has('signedUserId');
    }
    
    /**
     * Log out the authenticated user
     * 
     * @return  void
     */
    public static function deauthenticate() {
        if(isset($_COOKIE['cmVtZW1iZXI'])) {
            cookie('cmVtZW1iZXI', '', -7);
        }
        session()->flush();
        ch_logout();
    }

    /**
     * Create a valid password
     * 
     * @param   string  $string
     * @return  string
     */
    public static function password($str) {
        return hash_hmac('sha256', $str, env('APP_KEY'));
    }

    /**
     * Remember a user
     * 
     * @return  void
     */
    public static function remember() {
        if ( !static::check() && !is_null(cookie('cmVtZW1iZXI')) ) {
//            $remember_token = cookie('cmVtZW1iZXI');
//            $user = Database::table('users')->where('remember_token', $remember_token)->first();
//            if ( is_object($user) ) {
//                static::authenticate($user);
//            } else {
//                static::deauthenticate();
//            }
        }
    }

    
    /**
     * Get the authenticated user
     * 
     * @return \Std
     */
    public static function user() {
        return Database::table('users')->find(session('signedUserId') + 0);
    }

    public static function id() {
        return session('signedUserId') + 0;
    }

    public static int $company_id=0;

    public static function SetTimeZone() {
        if (env('SITE_Portal'))
            date_default_timezone_set('America/New_York');
        //Sync the MySQL and PHP datetimes
        Database::table('users')->synchronizeTimezone();
    }

    public static function setCid($cid) {
        Auth::$company_id=$cid;
        Auth::SetTimeZone();
    }

    public static function cid() {
        $cid=session('companySession') + 0;
        if ($cid>0)
            return $cid;
        if (Auth::$company_id>0)  //from cronjob
            return Auth::$company_id;

//        if ((!isset(Auth::user()->company) || Auth::user()->company>0)){
//            generateCallTrace();
//        }
        return 2; //Portal site
    }


    /**
     * Login a user
     * 
     * @param string $username
     * @param password $password
     * @param string $options
     * @return mixed
     */
    public static function login($username, $password, $options = array()) {
        $user = Database::table('users')->where('email',$username)->first();

        if (!empty($user)) {
            if (isset($options["status"])) {
                if ($options["status"] == $user->status) {
                    return array(
                        "status" => "error",
                        "title" => "Account inactive",
                        "message" => "Your account is not active."
                    );
                }
            }

            if(hash_compare($user->password, self::password($password))){
                if (isset($options["rememberme"]) && $options["rememberme"]) {
                    self::authenticate($user, true);
                }else{
                    self::authenticate($user);
                }

                if (isset($options['redirect'])) {
//                    $companies = CompanyModel::GetCompany();
                    $response = array(
                        "status" => "success",
                        "notify" => false,
                        "callback" => "redirect('', true);"
                    );
                }else{
                    $response = array(
                        "status" => "success",
                        "title" => "Login Successful",
                        "message" => "You have been logged in successfully"
                    );
                }
                
            }else{
                $response = array(
                    "status" => "error",
                    "title" => "Incorrect Credentials",
                    "message" => "Incorrect username or password"
                );
            }
        }else{
            $response = array(
                "status" => "error",
                "title" => "User not found",
                "message" => "Incorrect username or password"
            );
        }

        return $response;
    }
    
    /**
     * Sign up new user
     * 
     * @param array $data
     * @param array $options
     * @return mixed
     */
    public static function signup($data, $options = array()) {
        if (isset($options['uniqueEmail'])) {
            $email=$options['uniqueEmail'];
            $user = Database::table('users')->where('email',$email)->first();
            if (!empty($user)) {
                $message="Id:".$user->id." ".$user->fname." ".$user->lname." ".$user->status;
                return array(
                    "status" => "error",
                    "title" => $email." exists".' '.$user->flag,
                    "message" => $message
                );
            }
        }

        $res=Database::table("users")->insert($data);
        if (!$res){
            return array(
                "status" => "error",
                "title" => "error exists!",
                "message" => Database::$error
            );
        }
        $newUserId = Database::table("users")->insertId();
        if (isset($options["authenticate"]) AND $options["authenticate"]) {
            $user = Database::table('users')->where("id",$newUserId)->first();
            self::authenticate($user);
        }

        if (isset($options['redirect'])) {
            $response = array(
                "status" => "success",
                "notify" => false,
                "callback" => "redirect('".$options['redirect']."', true);"
            );
        }else{
            $response = array(
                "status" => "success",
                "user_id"=>$newUserId,
                "title" => "Sign up Successful",
                "message" => "Your account was created successfully"
            );
        }

        return $response;
    }
    
    /**
     * forgot password
     * 
     * @param string $email
     * @param string $resetlink
     * @return mixed
     */
    public static function forgot($email, $resetlink) {
        $user = Database::table('users')->where('email',$email)->first();
        if (!empty($user)) {

            $token = Str::random(32);
            $data = array('token' => $token);
            Database::table('users')->where('email' ,$email)->update($data);
            $resetLink = str_replace("[token]", $token, $resetlink);

            Mail::send(
                $email,
                env("APP_NAME")." Password Reset.",
                array(
                    "title" => "Password Reset",
                    "subtitle" => "Click the the button below to reset your password.",
                    "buttonText" => "Reset Password",
                    "buttonLink" => $resetLink,
                    "message" => "Someone hopefully you has requested a password reset on your account. If it is you go ahead and reset your password, if not please ignore this email."
                ),
                "withbutton"
            );
            $response = array(
                "status" => "success",
                "title" => "Email sent!",
                "message" => "Email with reset instructions successfully sent!",
                "callback" => "redirect('".url("Auth@get")."')"
            );
        }else{
            $response = array(
                "status" => "error",
                "title" => "Account not found",
                "message" => "Account with this email was not found"
            );
        }

        return $response;

    }
    
    /**
     * reset password
     * 
     * @param string $token
     * @param string $password
     * @return mixed
     */
    public static function reset($token, $password) {
        $user = Database::table('users')->where('token',$token)->first();
        if (!empty($user)) {
            $data = array('token' => "" , 'password' => self::password($password));
            $update = Database::table('users')->where("id",$user->id)->update($data);

            if ($update) {
                    $response = array(
                        "status" => "success",
                        "title" => "Password reset!",
                        "message" => "Password successfully reset!",
                        "callback" => "redirect('".url("Auth@get")."', true);"
                    );
            }else{
                    $response = array(
                        "status" => "error",
                        "title" => "Failed to reset",
                        "message" => "Failed to reset password, please try again"
                    );
            }
        }else{
            $response = array(
                "status" => "error",
                "title" => "Token Mismatch",
                "message" => "Token not found or expired."
            );
        }

        return $response;

    }
}