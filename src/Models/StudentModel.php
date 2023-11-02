<?php
namespace Simcify\Models;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Simcify\Auth;
use Simcify\Controllers\Zk;
use Simcify\Database;
use Simcify\DocSign;
use Simcify\File;

class StudentModel
{
    //Action
    const Active='Active';  //Student has completed check in process
    const Checked_Out='Checked Out';
    const Canceled='Canceled';
    const Suspended='Suspended'; //Admin has suspended student
    const Deleted='Deleted';

    //status
    const Created='Created';
    const Arrived='Arrived';
    const Extended='Extended';
    const Paused='Paused';
    const Terminated='Terminated';  //Student has completed checkout process

    //Sign_status
    const Pending='Pending';
    const Sent='Sent';
    const Signed='Signed';
    const Lease_on_File='Lease on File';

    static public $fields_monday=[
        'unit'=>['name'=>'Room', 'width'=>10],
        'fname'=>['name'=>'First Name', 'width'=>18],
        'lname'=>['name'=>'Last Name', 'width'=>18],
        'gender'=>['name'=>'Gender', 'width'=>10],
        'employer'=>['name'=>'Employer'],
        'sponsor'=>['name'=>'sponsor'],
        'country'=>['name'=>'Country'],
        'lease_start'=>['name'=>'Move In Date', 'width'=>16
        ],
        'checked_out_at'=>['name'=>'Move Out Date',  'width'=>16],
        'status'=>['name'=>'Status']
    ];

    static public $fields=[
        'fname'=>['name'=>'First Name'],
        'lname'=>['name'=>'Last Name'],
        'email'=>['name'=>'Email',
            'width'=>25
        ],
        'lease_start'=>['name'=>'Lease Start',
            'width'=>16
        ],
        'lease_end'=>['name'=>'Lease End',
            'width'=>16
        ],
        'country'=>['name'=>'Country'],
        'phone'=>['name'=>'Phone Number'],
        'address'=>['name'=>'Address'],
        'birthday'=>['name'=>'Birthday'],
        'city'=>['name'=>'City'],
        'state'=>['name'=>'State'],
        'zip'=>['name'=>'Zip Code',
            'width'=>10],
        'gender'=>['name'=>'Gender',
            'width'=>10],
        'employer'=>['name'=>'Employer'],
        'sponsor'=>['name'=>'sponsor',
            'formula'=>'"None,AAG,Intrax,CIEE,Interexchange,Greenheart,Spirit,Asse,Aspire,Geovision,CCI,CCUSA,GEC"'
        ],
        'phone_country'=>['name'=>'Phone Country'],
        'season'=>['name'=>'Season'],
        'identifier'=>['name'=>'Identifier']
    ];

    static public $mondayEmployers=[
        ['name'=>'Dollywood', 'id'=>102,'count'=>0],
        ['name'=>'Ironsure: Dollywood', 'id'=>142,'count'=>0],
        ['name'=>'Orange: Dollywood', 'id'=>143,'count'=>0],
        ['name'=>'SHIP: Dollywood', 'id'=>145,'count'=>0]
    ];

    static public function ReplaceUnit($unit){
        $unit=strtoupper($unit);
        $unit=str_replace('-A','A',$unit);
        $unit=str_replace('-B','B',$unit);
        $unit=str_replace('-C','C',$unit);
        $unit=str_replace('-D','D',$unit);
        $unit=str_replace('A','-A',$unit);
        $unit=str_replace('B','-B',$unit);
        $unit=str_replace('C','-C',$unit);
        $unit=str_replace('D','-D',$unit);
        return $unit;
    }

    static public function GetExcel($fields,$path){
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator(env('Site_Name'))
            ->setLastModifiedBy(env('Site_Name'))
            ->setTitle(env('Site_Name').' Students')
            ->setSubject(env('APP_NAME').' Students')
            ->setDescription(env('APP_NAME').' Students');
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->setTitle("Students");

        $field_len = count($fields) + 1;
        $end_alpha = chr(63 + $field_len);

        $students = Database::table("users")->company()->where("role", "user")->where("status", '!=',"Created")->where("status", '!=',"Terminated")->get();
        $len = count($students);

        $styleArray = [
            'font' => [
                'bold' => true
            ],
        ];
//First Row
        $worksheet->setCellValueByColumnAndRow(1, 1, env('Site_Name'));
        $worksheet->mergeCells('A1:D1');
        $worksheet->getStyle('A1:A1')->applyFromArray($styleArray);
        $worksheet->setCellValueByColumnAndRow(1, 2, 'Total Count is '.$len);
        $worksheet->mergeCells('A2:D2');
//Field Names
        $id = 0;
        foreach ($fields as $key => $value) {
            $id++;
            $worksheet->setCellValueByColumnAndRow($id, 3, $value['name']);
            $worksheet->getColumnDimensionByColumn($id)->setWidth(isset($value['width'])?$value['width']:12);
        }

        $worksheet->getStyle('A3:' . $end_alpha . '3')->applyFromArray($styleArray); //->getFont()->setSize(14)

        for ($i = 0; $i < $len; $i++) {
            $j = $i + 4;
            $id = 0;
            $student=$students[$i];

            //check Dollywood employer and update employer count
            foreach (self::$mondayEmployers as $id0=>$employer)
                if ($student->employer==$employer['id']){
                    self::$mondayEmployers[$id0]['count']++;
                    break;
                }

            $array = json_decode(json_encode($student), true);
            foreach ($fields as $key => $value) {
                $id++;
                if ($key=='checked_out_at'){
                    if ($student->status=='Terminated')
                        $array[$key]=$student->checked_out_at;
                    else
                        $array[$key]='';
                }
                else if ($key=='employer'){
                    $employer = Database::table("employers")->find($student->employer);
                    $array[$key]=isset($employer->name)?$employer->name:'';
                }
                $worksheet->setCellValueByColumnAndRow($id, $j, $array[$key]);
                if (isset($value['formula'])){
                    $validation = $worksheet->getCellByColumnAndRow($id, $j)->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST)
                        ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                        ->setAllowBlank(false)
                        ->setShowInputMessage(true)
                        ->setShowErrorMessage(true)
                        ->setShowDropDown(true)
                        ->setErrorTitle('Input error')
                        ->setError($value['name'].' is not in the list.')
                        ->setPromptTitle('Pick from the list')
                        ->setPrompt('Please pick a '.$value['name'].' from the drop-down list.')
                        ->setFormula1($value['formula']);
                }
            }
        }
        // Set the data table style
//            $styleArrayBody = [
//                'borders' => [
//                    'allBorders' => [
//                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
//                        'color' => ['argb' => '666666'],
//                    ],
//                ],
//                'alignment' => [
//                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
//                ],
//            ];
//
//            $total_rows = $len + 1;
//            //Add all borders/centered
//            $worksheet->getStyle('A1:' . $end_alpha . $total_rows)->applyFromArray($styleArrayBody);

        $today = date("Y-m-d");
        $filename = $today.' '.env('Site_Name').'.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Cache-Control: max-age=0');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($path);
        return array('total'=>$len);
    }

    static public function CreateUser() {
        $password = rand(111111, 999999);
        $user_data = array(
            "avatar" => '',
            "company" => Auth::cid(),
            "pin"=>$password,
            "password" => Auth::password($password)
        );

        return $user_data;
    }

    static public function GetUser($user_id) {
        $student= Database::table("users")->find($user_id);
        $user = Auth::user();
        if (isset($user) && $user->role == "user" && $student->id!=$user->id) {
            die (view('errors/404_permission'));
        }
        return $student;
    }

    public static function Update($id,$array) {
        return Database::table("users")->where("id" , $id)->update($array);
    }
// update balance of user
    public static function UpdateBalance($user,$amount) {
        $user->balance+=$amount;
        StudentModel::Update($user->id,array('balance' => $user->balance));
    }

    public static function UpdateSecurityDeposit($user,$securityDeposit) {
        if ($securityDeposit!=0){
            $user->security_deposit += $securityDeposit;
            Database::table("users")->where("id", $user->id)->update(array('security_deposit' => $user->security_deposit));
        }
    }

    public static function getPhoneCodeByIso($iso){
        $country_phone = Database::table("country_phone")->where("iso", $iso)->first();
        return $country_phone->phonecode;
    }

    public static function getPhoneNumber($user){
        return "+".StudentModel::getPhoneCodeByIso($user->phone_country). " " . $user->phone;
    }

    public static function getEmployerName($student){
        return $student->employer>0?Database::table('employers')->find($student->employer)->name:'None';
    }

    public static function getEmployerId($name){
        $first=Database::table('employers')->where("name","LIKE", "%".$name."%")->first();
        return $first?$first->id:0;
    }

    public static function convert($studentData){
        if (strtoupper($studentData['gender'])=='F')
            $studentData['gender']='Female';
        if (strtoupper($studentData['gender'])=='M')
            $studentData['gender']='Male';
        $studentData['employer']=StudentModel::getEmployerId($studentData['employer']);
        return $studentData;
    }

    public static function delete($user,$action){
        StudentModel::Update($user->id,array('status'=>StudentModel::Terminated,'bed_id'=>0,'action'=>$action));
        if ($user->bed_id>0){
            $bed=Database::table("beds")->where("id" ,$user->bed_id)->first();
            if ($bed->student_id==$user->id)
                RoomModel::SetVacant($user->bed_id);
        }

    }

    public static function SetTerminated($user,$action){
        $outstanding_balance=$user->security_deposit - $user->balance;
        $dd=array(
//            'room_id' => 0,'bed_id'=> 0,'unit'=> '',
            'email'=>($user->flag=='Violation')?$user->email: 'T'.$user->id.'_'.$user->email,
            'status'=> StudentModel::Terminated,
            'action'=> $action,
            'outstanding_balance'=>$outstanding_balance,
            'checked_out_at'=>date("Y-m-d H:i:s")
        );
        StudentModel::Update($user->id,$dd);
    }

    public static function Remove($user){
        if (!empty($user->avatar)) {
            File::delete($user->avatar, "avatar");
        }
        StudentModel::delete($user,StudentModel::Suspended);

        Database::table("passports")->where("user_id", $user->id)->delete();
        Database::table("roommates")->where("user_id", $user->id)->delete();
        Database::table("balance_history")->where("student_id", $user->id)->delete();
        Database::table("requests")->where("receiver", $user->id)->delete();
        Database::table("cancel_lease_history")->where("student_id", $user->id)->delete();
        Database::table("invoices")->where("student_id", $user->id)->delete();
        Database::table("maintenance_comments")->where("user_id", $user->id)->delete();
        Database::table("maintenance_requests")->where("user_id", $user->id)->delete();
        Database::table("users")->where("id", $user->id)->delete();

        if (env("SITE_Portal"))
            Zk::delete_person($user->id);
    }

    public static function AddNote($user,$student_id,$type,$content){
        $ad=array(
            'author_id'=>$user->id,
            'author_name'=>$user->fname.' '.$user->lname,
            'student_id'=>$student_id,
            'company' => Auth::cid(),
            'type'=>$type,
            'content'=>$content
        );
        Database::table('notes')->insert($ad);
    }
}