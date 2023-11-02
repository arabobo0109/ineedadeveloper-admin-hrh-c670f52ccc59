<?php
namespace Simcify\Models;

use Simcify\Auth;
use Simcify\Date;
use Simcify\Mail;
use Simcify\Database;
use function Simcify\print_r2;


class EmailModel {
    const Pending='Pending';
    const Sent='Sent';
    const Error='Error';

}