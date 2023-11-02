<?php
namespace Simcify\Controllers;

use Simcify\Models\RoomModel;
use Simcify\Str;
use Simcify\File;
use Simcify\Mail;
use Simcify\Auth;
use Simcify\Database;
use Simcify\Signer;
use function Simcify\print_r2;

class Room extends Admin{

    public function getList($page='') {
        if(isset($_REQUEST["builing"]))
            $rooms = Database::table("rooms")->where("building_id", $_REQUEST["builing"])->get();
        else
            $rooms = Database::table("rooms")->company()->get();
        $bed_statistic = RoomModel::getBedStatistic($rooms);

        $buildings = RoomModel::GetBuildings();

        $user = Auth::user();

        foreach ($rooms as $room) {
            $room->bed_data=RoomModel::getBedData($room->id);
        }
        return view('room'.$page, compact("user", "rooms", "buildings", "bed_statistic"));
    }

    public function getRoomList() {
        return $this->getList('/room_list');
    }

    public function create(){
        header('Content-type: application/json');
        $employer_data = array(
            "name" => $_POST["name"],
            "building_id" => $_POST["building_id"],
            'company' => Auth::cid()
        );

        Database::table("rooms")->insert($employer_data);

        // Action Log
        Customer::addActionLog("Room", "Create Room", "Created a Room : ". $_POST["name"]);

        exit(json_encode(responder("success","Alright", "Room successfully created","reload()")));
    }

    public function createBed(){
        header('Content-type: application/json');
        $employer_data = array(
            "name" => $_POST["name"],
            "room_id" => $_POST["room_id"]
        );

        Database::table("beds")->insert($employer_data);

        // Action Log
        Customer::addActionLog("Bed", "Create Bed", "Created a Bed : ". $_POST["name"]);

        exit(json_encode(responder("success","Alright", "Bed successfully created","reload()")));
    }

    //assign beds
    public function reviewRoom($room_id){
        $user = Auth::user();
        $room = Database::table("rooms")->where("id", $room_id)->first();
        $building = Database::table("buildings")->where("id", $room->building_id)->first();
        $beds = RoomModel::getBedData($room_id);

        return view('room/review', compact("user", "room", "building", "beds"));
    }

    public function delete() {
        $room = Database::table("rooms")->find(input("roomid"));

        Database::table("rooms")->where("id", input("roomid"))->delete();
        Database::table("beds")->where("room_id", input("roomid"))->delete();


        // Action Log
        Customer::addActionLog("Room", "Delete Room", "Deleted Room : ". $room->name );

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Room Deleted!", "Student successfully deleted.","reload()")));
    }

    public function ChangeBedStatus(){
        $bed = Database::table("beds")->find(input("bedid"));
        $status=input('status','');
        if ($status==''){
            $status=RoomModel::Vacant;//Dirty and Occupied
            if ($bed->status==RoomModel::Vacant)
                $status=RoomModel::Unavailable;
            elseif ($bed->status==RoomModel::Occupied)
                RoomModel::SetVacant($bed->id);
            elseif ($bed->status==RoomModel::Unavailable)
                $status=RoomModel::Dirty;
        }

        Database::table("beds")->where("id", $bed->id)->update(array('status'=>$status));
        // Action Log
        Customer::addActionLog("Bed", "Change Status", $bed->name." ".$status);

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Bed Status", "This Bed status was changed to ".$status,"reload()")));
    }

    public function deleteBed(){
        $bed = Database::table("beds")->find(input("bedid"));
        Database::table("beds")->where("id", input("bedid"))->delete();
        // Action Log
        Customer::addActionLog("Bed", "Delete Bed", "Deleted Bed : ". $bed->name );

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Bed Deleted!", "Bed successfully deleted.","reload()")));
    }

    public function updateViewBed(){
        $data = array(
            "bed" => Database::table("beds")->where("id", input("bedid"))->first()
        );
        return view('extras/update_bed', $data);
    }

    // get state names
    public function getRooms() {
        $json= Database::table("rooms")->where("building_id", input("countryID"))->get();
        header('Content-Type: application/json');
        echo json_encode($json);
    }

    public function updateBed() {

        foreach (input()->post as $field) {
            if ($field->index == "csrf-token" || $field->index == "bedid") {
                continue;
            }
            Database::table("beds")->where("id" , input("bedid"))->update(array($field->index => escape($field->value)));
        }

        // Action Log
        Customer::addActionLog("Bed", "Update Bed", "Changed Bed information: ");

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Alright!", "Bed was successfully updated","reload()")));
    }

    public function roomBedStatus(){
        $user = Auth::user();
        $buildings = RoomModel::GetBuildings();
        return view('room/room_bed_status', compact("user","buildings"));
    }

    function getBeds() {
        $json= Database::table("beds")->where("room_id", input("stateID"))->get();
//        foreach ($json as $bed){
//            $student= Database::table("users")->find($bed->student_id);
//            $bed->lease_start=
//        }
        header('Content-Type: application/json');
        echo json_encode($json);
    }

    function getStudentNameByID($student_id){
        $student = Database::table("users")->find($student_id);
        return $student->fname . " " . $student->lname;
    }
// called by room assignments
    function getBlockList() {
		$rows				= array();
		$rows_set			= array();
		$rows_static_col	= array();

		$building_feed = [];
		$building_id=input("building_id");
		if ($building_id==0){
            $building_id=Database::table("buildings")->company()->first()->id;
        }

        $rows_set['id'] = Room::autoId();
        $rows_static_col['colSpan'] = '2';
        $rows_static_col['id'] = Room::autoId();
        $rows_static_col['type'] = 'others';
        $rows_static_col['title'] = 'stair';
        $rows_set['cols'] = $rows_static_col;

        $rows[0] = $rows_set;
        $room_floor = [];

        $rooms_temp = Database::table("rooms")->where("building_id", $building_id)->orderBy("name",true)->get();
        foreach($rooms_temp as $key=> $each_room){

            $room_floor_number = (int)(($each_room->name - ((int)($each_room->name/1000)*1000) ) / 100);
            if($room_floor_number == 0)
                continue;

            $each_room_temp = array();
            $each_room_temp['id'] = $each_room->id;
            $each_room_temp['roomNo'] = $each_room->name;
            $each_room_temp['beds'] = RoomModel::getBlockBedData($each_room->id);
            $each_room_temp['floor'] = $room_floor_number;

            $room_floor[$room_floor_number-1][] = $each_room_temp;
        }

        $building_feed['floors'] = $room_floor;

        // ==================== Status Start=====================
        $bed_statistic = RoomModel::getBedStatistic($rooms_temp);
        $room_statistic = RoomModel::getRoomStatistic($rooms_temp);
        $building_feed['draw_status'] = $bed_statistic;
        $building_feed['draw_rooms_status'] = $room_statistic;

        $building_feed['total_beds'] = $bed_statistic['total_count'];
        $building_feed['total_rooms'] = count($rooms_temp);

        // ==================== All Status
        $rooms_all_temp = Database::table("rooms")->company()->get();
        $building_feed['all_status'] = RoomModel::getBedStatistic($rooms_all_temp);
        $building_feed['all_rooms_status'] = RoomModel::getRoomStatistic($rooms_all_temp);

		header('Content-Type: application/json');
        echo json_encode($building_feed, JSON_PRETTY_PRINT);
    }

	function autoId() {

		$autoId = md5(uniqid(rand(), true));

		return $autoId;
	}

    public static function getBuildingName($building_id){
        $building = Database::table("buildings")->where("id", $building_id)->first();
        return $building->name;
    }
    public static function getRoomName($room_id){
        $room = Database::table("rooms")->where("id", $room_id)->first();
        return $room->name;
    }
    public static function getBedName($bed_id){
        $bed = Database::table("beds")->where("id", $bed_id)->first();
        return $bed->name;
    }
}