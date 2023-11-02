<?php
namespace Simcify;

use Dotenv\Dotenv;
use PDO;
use PHPMailer\PHPMailer\PHPMailer;
use DI\ContainerBuilder;
use Simcify\Models\CompanyModel;
use function DI\object as di_object;
use function DI\factory;
use function DI\get;

class Application {

    /**
     * Initialise the application
     * 
     * @return  void
     */
    public function __construct() {
        $this->_loadEnv();
        $this->_makeContainer();
    }
    
    /**
     * Destroy the application
     * 
     * @return void
     */
    public function __destruct() {
        container(PDO::class, 0);
    }

    /**
     * Load environment variables
     * 
     * @return  void
     */
    protected function _loadEnv() {
        // Load the environment variables
        $dotenv = new Dotenv(__DIR__ . DIRECTORY_SEPARATOR . '..');
        $dotenv->load();
    }

    /**
     * Load environment variables
     * 
     * @return  \DI\Container
     */
    protected static function _makeContainer() {
        $config = Config::cache();
        // Create our new php-di container
        $builder = new ContainerBuilder();
        $builder->useAutowiring(true);
        $builder->addDefinitions([
            'config'            => $config,
            PDO::class          => di_object()->constructor(
                "mysql:host=".env('DB_HOST').";dbname=".env('DB_DATABASE'),
                env('DB_USERNAME'),env('DB_PASSWORD'),
                ['ATTR_DEFAULT_FETCH_MODE' => PDO::FETCH_OBJ]
            ),
            PHPMailer::class    => di_object()->constructor(true),
            'Simcify\Mailer'    => factory(function($mail) {
                $mail->SMTPDebug = 0;
                if (env("SMTP_AUTH")) {
                    $mail->isSMTP();
                }
                $mail->Host = env('SMTP_HOST');
                $mail->SMTPAuth = env("SMTP_AUTH");
                $mail->Username = env('MAIL_USERNAME');
                $mail->Password = env('SMTP_PASSWORD');
                $mail->SMTPSecure = env('MAIL_ENCRYPTION');
                $mail->Port = env('SMTP_PORT');
                return $mail;
            })->parameter('mail', get('PHPMailer\PHPMailer\PHPMailer')),
            Session::class      => di_object()
        ]);
        
        Container::setInstance($builder->build());
    }

    /**
     * Handle incoming requests
     * 
     * @return  void
     */
    public function route() {
        /**
         * The default namespace for route-callbacks, so we don't have to specify it each time.
         * Can be overwritten by using the namespace config option on your routes.
         */
        Router::setDefaultNamespace('\Simcify\Controllers');
        // Load application routes
        require_once 'routes.php';
        // Start the application's routing
        Router::start();
        Auth::cb_auth();
    }
}
