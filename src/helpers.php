<?php

use Simcify\Auth;
use Simcify\Container;
use Simcify\Database;
use Simcify\Config;
use Simcify\Router;
use Simcify\Session;
use Simcify\Str;

if(! function_exists('asset')) {
    /**
     * Generate a valid asset url
     * 
     * @param   string  $url
     * @return  mixed
     */
    function asset($url) {
        return substr(url($url), 0, -1);
    }
}


if(! function_exists('config')) {
    /**
     * Get a config value
     * 
     * @param   string  $str
     * @param   mixed   $value
     * @return  mixed
     */
    function config($str, $value = null) {
        if (is_null($value)) {
            return Config::get($str);
        }else {
            return Config::set($str, $value);
        }
        
    }
}

if(! function_exists('container')) {
    /**
     * Get/Set a config value
     * 
     * @param   string  $key
     * @param   mixed   $value
     * @return  mixed
     */
    function container($key, $value = null) {
        $container = Container::getInstance($key);
        if ( is_null($value) ) {
            return $container->get($key);
        } else {
            return $container->set($key, $value);
        }
    }
}

if (! function_exists('cookie')) {
    /**
     * Get/Set a cookie
     *
     * @param $key
     * @param null $value
     * @param int $days
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     * @return bool|null
     */
    function cookie($key, $value = null, $days = 1, $path = "/", $domain = "", $secure = false, $httponly = true) {
        if ( is_null($value) ) {
            return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
        } else {
            return setcookie($key, $value, time() + (86400 * $days), $path, $domain, $secure, $httponly);
        }
    }
}

if (! function_exists('env')) {
    /**
     * Gets the value of an environment variable. Supports boolean, empty and null.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function env($key, $default = null) {
        $value = getenv($key);

        if ($value === false) {
            return value($default);
        }
        else if ($value=='db'){
            $setting=Database::table("settings")->company()->where('name',$key)->get('value')[0];
            if (isset($setting))
                return $setting->value;

            return value($default);
        }

        switch ( strtolower($value) ) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return;
        }

        if ( strlen($value) > 1 && Str::startsWith($value, '"') && Str::endsWith($value, '"') ) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}

if (! function_exists('hash_compare')) {
    /**
     * Compare two string hashes
     * 
     * @param   string  $a
     * @param   string  $a
     * @return  boolean
     */
    function hash_compare($a, $b) {
        if ( !is_string($a) || !is_string($b) ) { 
            return false; 
        } 
        
        $len = strlen($a); 
        if ($len !== strlen($b)) { 
            return false; 
        } 

        $status = 0; 
        for ($i = 0; $i < $len; $i++) { 
            $status |= ord($a[$i]) ^ ord($b[$i]); 
        } 
        return $status === 0; 
    }
}

if (! function_exists('session')) {
    /**
     * Get the current session
     * 
     * @param   mixed   $key
     * @param   mixed   $value
     * @return  mixed
     */
    function session($key = null, $value = null) {
        $session = container(Session::class);
        if ( is_null($key) ) {
            return $session;
        } else if ( is_null($value) ) {
            return $session->get($key);
        } else {
            $session->put($key, $value);
        }        
    }
}

if (! function_exists('cid')) {
    function cid() {
        $cid=session('companySession') + 0;
        if ($cid>0)
            return $cid;
        if (Auth::$company_id>0)  //from cronjob
            return Auth::$company_id;
        return 2; //Portal site
    }
}

if (! function_exists('responder')) {
    /**
     * Return Json response
     * 
     * @param   mixed   $key
     * @param   mixed   $value
     * @return  mixed
     */
    function responder($status, $title, $message, $callback = null, $notify = true, $notifyType = null, $callbackTime = "onconfirm") {
        $response = array(
                "status" => $status,
                "title" => $title,
                "message" => $message
            );
        if (!empty($callback)) {
            $response["callback"] = $callback;
        }
        if (!$notify) {
            $response["notify"] = false;
        }
        if (isset($notifyType)) {
            $response["notifyType"] = $notifyType;
        }
        if ($callbackTime == "instant") {
            $response["callbackTime"] = $callbackTime;
        }
        $response["js"] = "console.log(\"OK\");";
        return $response;     
    }
}

if (! function_exists('is_404_pdf')) {
    /**
     * Return Json response
     * 
     * @param   string   $url
     * @return  boolean
     */
    function is_404_pdf($url) {
       $handle = curl_init($url);
       curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
       $response = curl_exec($handle);
       $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
       $mimeType = curl_getinfo($handle, CURLINFO_CONTENT_TYPE);
       curl_close($handle);
       if ($httpCode >= 200 && $httpCode < 300 && !empty($response) && $mimeType == 'application/pdf') {
           return false;
       } else {
           return true;
       }
    }
}

if (! function_exists('escape')) {
    /**
     * Return an escaped string
     * 
     * @param   string   $string
     * @return  string
     */
    function escape($string) {
        $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
        $string = htmlentities($string, ENT_QUOTES);
        return $string;     
    }
}

if (! function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (! function_exists('view')) {
    /**
     * Return a html page view
     * 
     * @param   string  $name
     * @param   array   $data
     * @return  string
     */
    function view($name = 'errors/404_permission.php', array $data = []) {
        $HTML_path = "views/{$name}.html";
        $PHP_path = "views/{$name}.php";
        $text_path = "views/{$name}.txt";
        ob_start();
        if (file_exists($PHP_path)) {
            include $PHP_path;
        } else if (file_exists($HTML_path)) {
            include $HTML_path;
        } else if (file_exists($text_path)) {
            include $text_path;
        } else {
            include 'views/errors/404_permission.php';
        }

        $search_n_replace = [
            '/{{/'                      => '<?= ',
            '/}}/'                      => '; ?>',
            '/\@for(\w*)\s*(\(.*\))/'   => '<?php for$1 $2 { ?>',
            '/\@if\s*(\(.*\))/'         => '<?php if $1 { ?>',
            '/\@elseif\s*(\(.*\))/'     => '<?php } else if $1 { ?>',
            '/\@else/'                  => '<?php } else { ?>',
            '/\@end\w+/'                => '<?php } ?>',
        ];

        $view = preg_replace(
            array_keys($search_n_replace),
            array_values($search_n_replace),
            '?>' . ob_get_contents() . '<?php return;'
        );
        ob_clean();
        foreach($data as $var => $val) {
            ${$var} = $val;
        }
        eval($view);

        return ob_get_clean();
    }
}

if (! function_exists('__')) {
    /**
     * Get the translated value of the set language
     * 
     * @param   string  $name
     * @return  string
     */
    function __($name) {
        $dot_keys = explode('.', $name);
        $PHP_path = str_replace("config", "lang", __DIR__)."/en_US/{$dot_keys[0]}.php";
        if (file_exists($PHP_path)) {
            $value = include $PHP_path;
            if(count($dot_keys) > 1) {
                for($x = 1; $x < count($dot_keys); $x++) {
                    $value = $value[$dot_keys[$x]];
                }
            }
            return $value;
        } else {
            return $name;
        }
    }
}

if (! function_exists('getUserIpAddr')) {

    /**
     * Get the user's IP address
     *
     * @return  string
     */
    function getUserIpAddr() {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
        //return gethostbyname(trim(`hostname`));
    }
}

if (! function_exists('setToUserTimezone')) {

    /**
     * Get the user's IP address
     *
     * @return  string
     */
    function setToUserTimezone($datetime) {
        $dt=new DateTime($datetime);
        return date("F j, Y. H:i", strtotime(($dt->format('Y-m-d H:i:s'))));
    }
}

if (! function_exists('generateCallTrace')) {

    /**
     * Get the user's IP address
     *
     * @return  string
     */
    function generateCallTrace() {
        $e = new Exception();
        $trace = explode("\n", $e->getTraceAsString());
        // reverse array to make steps line up chronologically
        $trace = array_reverse($trace);
        array_shift($trace); // remove {main}
        array_pop($trace); // remove call to this method
        $length = count($trace);
        $result = array();

        for ($i = 0; $i < $length; $i++)
        {
            $result[] = ($i + 1)  . ')' . substr($trace[$i], strpos($trace[$i], ' ')); // replace '#someNum' with '$i)', set the right ordering
        }

        $res= "\t" . implode("\n\t", $result);

        error_log(date("Y-m-d H:i:s")." cid=0\n", 3,DB_Log);
        if (isset($_SERVER['HTTP_HOST']))
            error_log(($_SERVER['HTTP_HOST']), 3,DB_Log);
        if (isset($_SERVER['REQUEST_URI']))
            error_log(($_SERVER['REQUEST_URI']), 3,DB_Log);
        error_log("\n", 3,DB_Log);
        error_log($res."\n\n", 3,DB_Log);
    }
}