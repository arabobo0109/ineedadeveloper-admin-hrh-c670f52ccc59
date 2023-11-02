<?php
namespace Simcify\Models;

use Simcify\Database;

class PaymentModel {

    static public function getStudentRoomFee($student) {
        $fees = CompanyModel::GetCompany();
        $special_room = Database::table("special_room")->where("bed_id", $student->bed_id)->first();
        $week_fee = $fees->weekly;
        $day_fee = $fees->daily;
        if(!empty($special_room)){
            $week_fee = $special_room->weekly;
            $day_fee = $special_room->daily;
        }
        if($student->weekly_rate != 0 ){
            if($student->weekly_rate != $fees->weekly ){
                $week_fee = $student->weekly_rate;
                $day_fee = (int)($week_fee/7);
            }
        }

        $return_fee = array();
        $return_fee['week_fee'] = $week_fee;
        $return_fee['day_fee'] = $day_fee;

        return $return_fee;
    }
}