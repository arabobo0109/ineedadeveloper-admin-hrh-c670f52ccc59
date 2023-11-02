<?php

$len=86;
echo "<br/><br/>Site<br />";
$error_logs='';
exec('tail -100 /var/log/apache2/error.portal.log', $error_logs);

foreach($error_logs as $error_log) {

    echo "<br />".substr($error_log, $len);
}

echo "<br/><br/>DB Log<br />";
$error_logs='';
exec('tail -100 /var/www/irhliving/log/db.log', $error_logs);

foreach($error_logs as $error_log) {
    echo "<br />".$error_log;
}
?>