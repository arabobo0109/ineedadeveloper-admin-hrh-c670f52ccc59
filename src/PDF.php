<?php 
namespace Simcify;

require_once 'TCPDF/tcpdf.php';
require_once 'TCPDF/tcpdi.php';

use TCPDF;
use TCPDI;


class PDF extends TCPDI {
    var $_tplIdx;
    var $numPages;
    function Header() {}
    function Footer() {}

    public function PutImage($action,$scale){
        $imageArray = explode( ',', $action['image'] );
        $imgdata = base64_decode($imageArray[1]);
        $this->Image('@'.$imgdata, round($action['xPos']*$scale), round($action['yPos']* $scale), round($action['width']* $scale), round($action['height']* $scale), '', '', '', false);
    }

    public function PutText($action,$scale,$text){
        $this->SetFont($action['font'], $action['bold'].$action['italic'], $action['fontsize'] - 6);
        $this->writeHTMLCell( round(($action['width'] + 90)*$scale), round($action['height']*$scale), round($action['xPos']* $scale) - 3, round($action['yPos']* $scale), str_replace("%22", '"', $text), 0, 0, false, true, '', true );
    }
}

