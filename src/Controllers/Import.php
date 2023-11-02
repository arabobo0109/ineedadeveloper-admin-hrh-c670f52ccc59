<?php

namespace Simcify\Controllers;

use Exception;
use Simcify\DocSign;
use Simcify\Models\BalanceModel;
use Simcify\Models\CompanyModel;
use Simcify\Models\OrderModel;
use Simcify\Str;
use Simcify\File;
use Simcify\Mail;
use Simcify\Auth;
use Simcify\Database;
use Simcify\Signer;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Simcify\Models\StudentModel;
use PDO;

class Import
{
    public function get_errors() {
        if (input('file')){
            $fileName=input('file');
            $targetPath = getcwd() .'/uploads/excel/' . $fileName;
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($targetPath);
            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
            $writer->save('php://output');
            exit();
        }

        $company = CompanyModel::GetCompany();
        $user = Auth::user();
        return view('user_errors', compact("user",'company'));
    }

    public function Excel()
    {
        try {
            if (input('action') == 'export') {
                StudentModel::GetExcel(StudentModel::$fields,'php://output');
            }
            else { //import excel
                $allowedFileType = [
                    'application/vnd.ms-excel',
                    'text/xlsx',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ];
//                if (in_array($_FILES["file"]["type"], $allowedFileType)) {
                    $targetPath = getcwd() .'/uploads/excel/' . $_FILES['file']['name'];
                    move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
                $spreadSheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($targetPath);
                    $excelSheet = $spreadSheet->getActiveSheet();
                    $spreadSheetAry = $excelSheet->toArray();
                    $sheetCount = count($spreadSheetAry);
                    $resMessage="Student Data was added successfully.";
                    $errorCnt=0;
                    for ($i = 1; $i < $sheetCount; $i++) {
                        $col=-1;
                        $studentData= StudentModel::CreateUser();
                        foreach (StudentModel::$fields as $key => $value) {
                            $col++;
                            $studentData[$key]=$spreadSheetAry[$i][$col];
                        }
                        $col++; //Is Imported?
                        if ($spreadSheetAry[$i][$col]=='Yes')
                            continue;
                        $col++; // +1 when write.
                        $studentData=StudentModel::convert($studentData);
                        $studentData['weekly_rate']=input('weekly');

//                        print_r($studentData);
                        $res=Database::table("users")->insert($studentData);
                        if (!$res){
                            $errorCnt++;
                            $resMessage="The excel has ". $errorCnt." errors. Please edit downloaded file and upload again.";
                            $excelSheet->setCellValueByColumnAndRow( $col+1,$i+1, Database::$error);
                            $excelSheet->setCellValueByColumnAndRow( $col,$i+1, 'No');
                        }
                        else{
                            $excelSheet->setCellValueByColumnAndRow( $col,$i+1, 'Yes');
                            $user_id=Database::table("users")->insertId();
                            $user=Database::table("users")->find($user_id);

                            $fees=(object)[
                                'security'=>input('security'),
                                'administration'=>input('administration'),
                                'laundry'=>input('laundry')
                            ];
                            OrderModel::Add($user,$fees);
                            if (strpos($user->email,'@irhliving.com') ===false)
                                DocSign::sendAgreement($user->email,$user->id);
                        }
                    }

                $date = date('Y-m-d'.substr((string)microtime(), 1, 8));
                $date = str_replace(".", "", $date);
                $filename = "result_".$date.".xlsx";

                $writer = new Xlsx($spreadSheet);
                $writer->save( getcwd() .'/uploads/excel/'.$filename);
                header('Content-type: application/json');
                $url = url("Import@get_errors").'?file='.$filename;
                ob_end_clean();
                exit(json_encode(responder("success", "Import!", $resMessage, "redirect('".$url."')")));
//                } else {
//                    exit(json_encode(responder("error", "Import!", "Please Upload Excel File.")));
//                }

            }
        }
        catch(Exception $e) {
            echo '\nCaught exception: ',  $e->getMessage(), "\n";
        }
    }

}
