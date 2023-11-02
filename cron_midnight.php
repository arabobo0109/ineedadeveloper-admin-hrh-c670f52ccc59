<?php
include_once 'vendor/autoload.php';

use Simcify\Application;
use Simcify\Auth;
use Simcify\Database;
use Simcify\Mail;
use Simcify\Models\DrawerModel;

//0 0 * * * cd /var/www/irhliving && php -q cron_midnight.php company_id=1
//0 22 * * * cd /var/www/irhliving && php -q cron_midnight.php company_id=2

$app = new Application();

parse_str(implode('&', array_slice($argv, 1)), $_GET);
Auth::setCid($_GET['company_id']);
close_open_batch();

function close_open_batch(){
    $open_batch = Database::table("drawer_batch")->company()->where("user_id", 1)->last();  //daily batch
    DrawerModel::CloseBatch($open_batch, $open_batch->closing_amount);

    /*  Create new Batch   */
    $new_drawer_number = DrawerModel::CreateBatch(0,1);
}


?>