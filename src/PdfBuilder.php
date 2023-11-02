<?php 
namespace Simcify;

class PdfBuilder{
    public static function Build($document, $actions, $docWidth, $tenant=null, $guestMode=true,$signature=null){
        $pdf = new PDF(null, 'px');
        $pdf->SetAutoPageBreak(FALSE, PDF_MARGIN_BOTTOM);
        $pdf->numPages = $pdf->setSourceFile(config("app.storage")."files/".$document->filename);
        $templateFields = array($docWidth);

        $signed = $editted = false;
        foreach(range(1, $pdf->numPages, 1) as $page) {
            $degree = 0;
            try {
                $pdf->_tplIdx = $pdf->importPage($page);
            }
            catch(\Exception $e) {
                return false;
            }
            foreach($actions as $action) {
                if(((int) $action['page']) === $page && $action['type'] == "rotate") {
                    $editted = true;
                    $degree = $action['degree'];
                    break;
                }
            }
            $size = $pdf->getTemplateSize($pdf->_tplIdx);
            $scale = round($size['w'] / $docWidth, 3);
            $pdf->AddPage(Signer::orientation($size['w'], $size['h']), array($size['w'], $size['h'], 'Rotate'=>$degree), true);
            $pdf->useTemplate($pdf->_tplIdx);
            foreach($actions as $action) {
                if(((int) $action['page']) === $page) {
                    if ($action['group'] == "input") {
                        if ($tenant!=null && $action['type'] =='text'){ //build new PDF from template
                            $text="";
                            if ($action['text']=='created_date')
                                $text=date("F j, Y");
                            elseif ($action['text']=='tenant_name')
                                $text=$tenant->fname.' '.$tenant->lname;
                            elseif ($action['text']=='lease_start_date')
                                $text=date("F j, Y", strtotime($tenant->lease_start));
                            elseif ($action['text']=='lease_end_date')
                                $text=date("F j, Y", strtotime($tenant->lease_end));
                            elseif ($action['text']=='weekly_rate')
                                $text='$'.$tenant->weekly_rate;
                            elseif ($action['text']=='two_weeks_fee')
                                $text='$'.$tenant->weekly_rate*2;
                            elseif ($action['text']=='one_weeks_fee')
                                $text='$'.$tenant->weekly_rate;
                            elseif ($action['text']=='total_fee'){
                                $text='$'.($tenant->weekly_rate*2+300);
                                if (env('SITE_Portal'))
                                    $text='$'.($tenant->weekly_rate+300);
                            }
                            elseif ($action['text']=='tenant_email')
                                $text=$tenant->email;
                            elseif ($action['text']=='print_name')
                                $text=$tenant->fname.' '.$tenant->lname;
                            elseif ($action['text']=='print_date')
                                $text=date("F j, Y");

                            if (!empty($text))
                                $pdf->PutText($action,$scale,'<div style="color:'.$action['color'].';">'.$text.'</div>');
                            else
                                $templateFields[] = $action;
                        }
                        else
                            $templateFields[] = $action;
                    }elseif ($action['type'] == "image") {
                        $editted = true;
                        $pdf->PutImage($action,$scale);

                    }elseif ($action['type'] == "symbol" || $action['type'] == "shape" || $action['type'] == "stamp") {
                        $editted = true;
                        $svg = str_replace("%22", '"', $action['image']);
                        $pdf->ImageSVG('@'.$svg, self::scale($action['xPos'], $scale), self::scale($action['yPos'], $scale), self::scale($action['width'], $scale), self::scale($action['height'], $scale), '', '', '', 0, false);
                    }else if ($action['type'] == "drawing") {
                        $editted = true;
                        $imageArray = explode( ',', $action['drawing'] );
                        $imgdata = base64_decode($imageArray[1]);
                        $pdf->Image('@'.$imgdata, 0, 0, $size['w'], $size['h'], '', '', '', false);
                    }else if ($action['type'] == "signature" || $action['type'] == "Initials" ) {
                        $signed = true;
                        if (!$guestMode) {
                            $pdf->Image($signature, self::scale($action['xPos'], $scale), self::scale($action['yPos'], $scale), self::scale($action['width'], $scale), self::scale($action['height'], $scale), '', '', '', false);
                        }else{ //Guest Mode - now working mode
                            $pdf->PutImage($action,$scale);
                        }
                    }elseif ($action['type'] == "text") {
                        $editted = true;
                        $pdf->PutText($action,$scale,$action['text']);
                    }
                }
            }
        }

        $pdf->Output(config("app.storage")."/files/". $document->outputName, 'F');

        $dd=array("filename" => $document->outputName, "editted" => "Yes");
        if (count($templateFields) > 1)
            $dd['template_fields']=json_encode($templateFields, JSON_UNESCAPED_UNICODE);
        Database::table("files")->where("id", $document->id)->update($dd);

        return $templateFields;
    }


    /**
     * Scale element dimension
     *
     * @param   int $dimension
     * @return  int
     */
    public static function scale($dimension, $scale) {
        return round($dimension * $scale);
    }

}

