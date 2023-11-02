<?php
namespace Simcify\Models;

use Simcify\Auth;
use Simcify\Controllers\Room;
use Simcify\Database;

class RoomModel
{
    const Vacant='Vacant';
    const Occupied='Occupied';
    const Unavailable='Unavailable';
    const Dirty='Dirty';
    const Maintenance='Maintenance';

    public static function SetVacant($bed_id){
        if ($bed_id>0){  //previous bed should be Vacant status.
            Database::table("beds")->where("id" ,$bed_id)->update(array('student_id' => 0,'status'=>RoomModel::Vacant));
        }
    }

    public static function getBedData($room_id) {
        $bed_temps = Database::table("beds")->where("room_id", $room_id)->get();
        foreach($bed_temps as $each_bed){
            $student = Database::table("users")->find($each_bed->student_id);
            $each_bed->student_name = $student->fname . " " . $student->lname;
            $each_bed->student_name ="<a href='".url('Customer@profile').$student->id."'>".$each_bed->student_name."</a>";
        }
        return $bed_temps;
    }

    public static  function getBedStatistic($room_array){
        $return_array = array();
        $total_count = 0;
        $occupied_count = $created_count =0;
        $vacant_count = 0;
        $dirty_count=0;
        foreach($room_array as $each_room){
            $bed_data = Database::table("beds")->where("room_id", $each_room->id)->get();
            foreach($bed_data as $each_bed){
                $total_count++;
                if($each_bed->status == RoomModel::Occupied){
                    $student = Database::table("users")->find($each_bed->student_id);
                    if ($student->status==StudentModel::Created)
                        $created_count++;
                    else
                        $occupied_count++;
                }
                else if($each_bed->status == RoomModel::Vacant)
                    $vacant_count++;
                else if($each_bed->status == RoomModel::Dirty)
                    $dirty_count++;
            }
        }
        $return_array["total_count"] = $total_count;
        $return_array["created_count"] = $created_count;
        $return_array["occupied_count"] = $occupied_count;
        $return_array["vacant_count"] = $vacant_count;
        $return_array["dirty_count"] = $dirty_count;
        $return_array["unavailable_count"] = $total_count-$created_count-$occupied_count-$vacant_count-$dirty_count;
        if ($total_count>0){
            $return_array["created_percent"] = round(($created_count / $total_count * 100), 1);
            $return_array["occupied_percent"] = round(($occupied_count / $total_count * 100), 1);
            $return_array["vacant_percent"] = round(($vacant_count / $total_count * 100), 1);
            $return_array["dirty_percent"] = round(($dirty_count / $total_count * 100), 1);
            $return_array["unavailable_percent"] = round(($return_array["unavailable_count"] / $total_count * 100), 1);
        }
        return $return_array;
    }
    public static  function getRoomStatistic($room_array){
        $return_array = array();
        $total_count = count($room_array);
        $occupied_count = $created_count =0;
        $vacant_count = 0;
        foreach($room_array as $each_room){
            $bed_data = Database::table("beds")->where("room_id", $each_room->id)->get();
            $bed_occupied_count = 0;
            $bed_vacant_count = 0;
            foreach($bed_data as $each_bed){
                if($each_bed->status == RoomModel::Occupied){
                    $student = Database::table("users")->find($each_bed->student_id);
                    if ($student->status!=StudentModel::Created)
                        $bed_occupied_count++;
                }
                else if($each_bed->status == RoomModel::Vacant)
                    $bed_vacant_count++;
            }
            if($bed_occupied_count) $occupied_count++;
            else if($bed_vacant_count == 4) $vacant_count++;
        }
        $return_array["total_count"] = count($room_array);
        $return_array["occupied_count"] = $occupied_count;
        $return_array["vacant_count"] = $vacant_count;
        $return_array["unavailable_count"] = $total_count-$occupied_count-$vacant_count;
        if ($total_count>0){
            $return_array["occupied_percent"] = round(($occupied_count / $total_count * 100), 1);
            $return_array["vacant_percent"] = round(($vacant_count / $total_count * 100), 1);
            $return_array["unavailable_percent"] = round(($return_array["unavailable_count"] / $total_count * 100), 1);
        }
        return $return_array;
    }
//called by Room assignment page by ajax
    public static function getBlockBedData($room_id) {
        $beds = array();
        $temp_bads = array();

        $bed_temps = Database::table("beds")->where("room_id", $room_id)->get();
        foreach($bed_temps as $each_bed){
            $temp_bads['id'] = $each_bed->id;
            $temp_bads['status'] = $each_bed->status;
            $temp_bads['bedName'] = $each_bed->name;

            $temp_user = array();
            $student = Database::table("users")->find($each_bed->student_id);

            if(!empty($student)) {
                $temp_user['id'] 	= $student->id;
                $temp_user['name'] 	= $student->fname . " " . $student->lname;
                $temp_user['gender'] = $student->gender;
                $temp_user['avatar'] = $student->avatar;
                $temp_user['intern'] = $student->intern;
                $temp_user['country'] = $student->country;
                $temp_user['status'] = $student->status;
                $temp_user['url'] = url('Customer@profile').$student->id;
                if($student->identifier == "")
                    $temp_user['identifier'] = "Not Set";
                else
                    $temp_user['identifier'] = $student->identifier;

                $temp_bads['user'] = $temp_user;
            } else {
                $temp_bads['user'] = NULL;
            }

            array_push($beds, $temp_bads);
        }
        return $beds;
    }

    public static function GetBuildings(){
        return Database::table("buildings")->company()->get();
    }
}