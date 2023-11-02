{{ view("includes/head", $data); }}
<style>
.content {
  margin: 0 auto;
}

/*do not remove this*/
.tooltip.right {
  padding: 0 5px;
  margin-left: -5px;
}

.tooltip-inner {
  max-width: 200px;
  padding: 3px 8px;
  color: #fff;
  text-align: left;
  background-color: #000;
  border-radius: 4px;
}
</style>

<body style="background-color: #fff;">
  <!-- header start -->
  {{ view("includes/header", $data); }}

  <div class="content room-bed-status">
    <div class="row">
      <div class="room-page" style="display: inline;">
        <div style="margin-left: 30px; color: #0FFF08; font-size: 22px; font-weight: bold; padding-top: 10px">Total Beds
        </div>
        <div class="bed-statitic" style="float: left;margin: 0;border: none; padding-top: 5px">
          <div style="margin-left: 40px; color: #240000;">
            <span>Dirty :</span>
            <span id="span-dirty"></span>
          </div>
          <div style="margin-left: 40px; color: #5F220E;">
            <span>Unavailable :</span>
            <span id="span-unavailable"></span>
          </div>
          <div class="color-vacan" style="margin-left: 40px;">
            <span>Vacant :</span>
            <span id="span-vacant"></span>
          </div>
          <div class="color-occupied" style="margin-left: 40px;">
            <span>Occupied : </span>
            <span id="span-occupied"></span>
          </div>
          <div class="color-created" style="margin-left: 40px;">
            <span>Created : </span>
            <span id="span-created"></span>
          </div>
          <div class="color-red" style="margin-left: 40px;">
            <span>Total : </span>
            <span id="span-total"></span>
          </div>
        </div>
        <div class="navbar_section">
          <ul class="nav nav-pills building-tab-area">
          </ul>
        </div>
        <br />
        <hr style="margin-left: 30px;" />
        <div style="margin-left: 30px; color: #0FFF08; font-size: 22px; font-weight: bold; margin-top: 20px">Total Rooms
        </div>
        <div class="bed-statitic" style="float: left;margin: 0;border: none; padding-top: 5px">
          <div style="margin-left: 40px; color: #5F220E;">
            <span>Unavailable :</span>
            <span id="span-room-unavailable"></span>
          </div>
          <div class="color-vacan" style="margin-left: 40px;">
            <span>Vacant :</span>
            <span id="span-room-vacant"></span>
          </div>
          <div class="color-occupied" style="margin-left: 40px;">
            <span>Occupied : </span>
            <span id="span-room-occupied"></span>
          </div>
          <div class="color-red" style="margin-left: 40px;">
            <span>Total : </span>
            <span id="span-room-total"></span>
          </div>
        </div>
      </div>
      <div class="col-md-12" style="background: #fff;margin-top: 20px;">
        <aside class="col-md-3 d-flex" style="padding: 8px;">
          <div style="padding: 8px;">
            <h2>Building Status</h2>
          </div>
          <div class="d-flex flex-column w-100">
            <div class="card" style="margin-top: 8px;">
              {{ view("room/donut_chart", ['title' => "Current Beds Status", 'id' => 'svg-beds']); }}
            </div>
            <div class="card flex-grow-1">
              <div class="card-body overflow-auto ">
                <span class="total-beds"></span>
              </div>
            </div>
          </div>
          <div class="d-flex mt-3 flex-column w-100">
            <div class="card">
              {{ view("room/donut_chart", ['title' => "Current Rooms Status", 'id' => 'svg-rooms']); }}
            </div>
            <div class="card flex-grow-1">
              <div class="card-body overflow-auto ">
                <span class="total-rooms"></span>
              </div>
            </div>
          </div>
        </aside>
        <main class="col-md-9">
          <div class="main-content">
            <table class="table table-borderless react-draggable react-draggable-dragged" data-table="Building table"
              style="transform: translate(0px, 0px);">
              <tbody>
                <tr class="floor-content-area">
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-md-12 box_info">
            <span class="box_created">&nbsp; </span> &nbsp; Created
            <span class="box_occupied">&nbsp; </span> &nbsp; Occupied
            <span class="box_vacant">&nbsp; </span> &nbsp; Vacant
            <span class="box_unavailable"> </span> &nbsp; Unavailable
            <span class="box_dirty">&nbsp; </span> &nbsp; Dirty
            <span class="box_maintenance"> </span> &nbsp; Maintenance
            <span class="box_intern">&nbsp; </span> &nbsp; Intern
          </div>
        </main>
      </div>
    </div>
  </div>

  <div id="popup-root" class="hidden">
    <div class="popup-content">
      <table class="tooltol">
        <tbody>
        </tbody>
      </table>
    </div>
  </div>

  <div class="modal fade" id="change_status_dlg" role="dialog" style="background: white">
    <div class="close-modal" data-dismiss="modal">&times;</div>
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Change Status</h4>
        </div>
        <form class="simcy-form" action="<?=url("Room@ChangeBedStatus");?>" data-parsley-validate="" loader="true"
          method="POST" enctype="multipart/form-data">
          <input type="hidden" name="bedid" value="0" />
          <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
          <div class="modal-body" style="height: 150px;">
            <label>Select a status</label><label class="color-red">*</label>
            <div class="form-group">
              <div class="row">
                <?php $statuses=['Vacant','Occupied','Unavailable','Dirty','Maintenance'] ?>
                @foreach($statuses as $status )
                <div class="col-md-4">
                  <label class="radio"><input type="radio" name="status" value="{{$status}}"><span class="outer"><span
                        class="inner"></span></span>{{$status}}</label>
                </div>
                @endforeach
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Change</button>
          </div>
        </form>
      </div>

    </div>
  </div>

  <!-- footer -->
  {{ view("includes/footer"); }}

  <script src='https://cdnjs.cloudflare.com/ajax/libs/snap.svg/0.3.0/snap.svg-min.js'></script>
  <script src="<?=url("");?>assets/js/svg-donut-chart-framework.js"></script>
  <script>
  let baseUrl = '<?=url("");?>';
  let csrf = '<?=csrf_token();?>';
  let building_id = '<?=input('building');?>';

  add_building_tab();

  $(document).ready(function() {
    reload_data();
  });

  function change_status(bed_id) {
    $('input[name="bedid"]').val(bed_id);
  }

  function reload_data() {
    $.ajax({
      url: "/room/block-list?building_id=" + building_id,
      type: "get",
      data: {},
      success: function(data) {
        console.log(data)
        drawChart(data.draw_status, "svg-beds");
        drawChart(data.draw_rooms_status, "svg-rooms", [
            {
                value: data.draw_rooms_status.occupied_percent==100?99.99:data.draw_rooms_status.occupied_percent,
                label: 'Occupancy '+data.draw_rooms_status.occupied_count+'('+data.draw_rooms_status.occupied_percent+'%)',
                color: '#d9b300'
            },
            {
                value: data.draw_rooms_status.vacant_percent==100?99.99:data.draw_rooms_status.vacant_percent,
                label: 'Vacant '+data.draw_rooms_status.vacant_count+'('+data.draw_rooms_status.vacant_percent+'%)',
                color: '#118dff'
            },
            {
                value: data.draw_rooms_status.unavailable_percent==100?99.99:data.draw_rooms_status.unavailable_percent,
                label: 'Unavailable '+data.draw_rooms_status.unavailable_count+'('+data.draw_rooms_status.unavailable_percent+'%)',
                color: '#5F220E'
            },
        ]);
        const floor_data = data['floors'];
        add_floor_content(floor_data);
        add_side_beds(data);
        add_side_rooms(data);
        add_tooltips(floor_data);
      }
    });
  }

  function add_building_tab() {
    const buildings = <?=json_encode($data['buildings'])?>;

    console.log(buildings);
    let temp_cont = '';
    buildings.forEach(function(building) {
      let isActive = (building.id == building_id) ? 'active' : '';
      temp_cont += '<li class="nav-item"> <a class="nav-link nav_button ' + isActive + '" href="?building=' +
        building.id + '" aria-current="page">' + building.name + '</a> </li>';
    });
    $(".building-tab-area").html(temp_cont);
  }

  function add_floor_content(floor_data) {
    var floor_str = "";
    let floors_prefix = ["1st", "2nd", "3rd", "4th"];
    const propNames = Object.keys(floor_data);
    propNames.forEach((i) => {
      var each_floor = floor_data[i];
      // console.log(i,each_floor);
      var room_str = "";
      var left_right_position = true;

      floor_str += '<td><table class="table table-borderless"><tbody><tr><td><h2>' + floors_prefix[i] +
        ' Floor</h2> </td> </tr><tr> <td> <table class="table floor_table" data-table="Floor table"> <tbody> <tr> <td class="room-type-td-others" colspan="2">STAIR</td> </tr>';

      for (j = 0; j < each_floor.length; j++) {
        var each_room = each_floor[j]['beds'];
        var bed_str = '';
        for (k = 0; k < each_room.length; k++) {
          // console.log(each_room[k]);
          bed_str += '<td class="p-1 bed-' + each_room[k]['id'] + '" data-toggle="tooltip"> <span class="room_box ';
          if (each_room[k]['status'] == "Occupied" && each_room[k]['user'] !== null) {
            if (each_room[k]['user']['status'] === 'Created')
              bed_str += 'box_created';
            else if (each_room[k]['user']['intern'])
              bed_str += 'box_intern';
            else
              bed_str += 'box_occupied';
          } else
            bed_str += 'box_' + each_room[k]['status'].toLowerCase();

          bed_str += '" aria-describedby="popup-1">' + each_room[k]['bedName'] + '</span> </td>';

        }
        if (left_right_position) {
          room_str +=
            '<tr><td class="room-type-td-room"><table class="table mb-0 bed_table" data-table="Beds Table"><tbody> <tr>';
          room_str += ' <td class="w_room p-1">' + each_floor[j]["roomNo"] + '</td>';
          room_str += bed_str;
          room_str += ' </tr> </tbody> </table></td>';
        } else {
          room_str +=
            '<td class="room-type-td-room"><table class="table mb-0 bed_table" data-table="Beds Table"><tbody> <tr>';
          room_str += bed_str;
          room_str += ' <td class="w_room p-1">' + each_floor[j]["roomNo"] + '</td>';
          room_str += ' </tr> </tbody> </table></td></tr>';
        }

        left_right_position ? (left_right_position = false) : (left_right_position = true);
      }

      floor_str += room_str;

      floor_str +=
        '</tr> <tr> <td class="room-type-td-others" colspan="2">STAIR</td> </tr> </tbody> </table> </td> <td width="20"></td> </tr></tbody> </table> </td>';
      $(".floor-content-area").html(floor_str);
    });
  }

  function add_side_beds(content_data) {
    $(".total-beds").html("Total Beds of current building : " + content_data['total_beds']);
    $("#span-total").html(content_data['all_status']['total_count']);
    $("#span-occupied").html(content_data['all_status']['occupied_count'] + '(' + content_data['all_status'][
      'occupied_percent'
    ] + '%)');
    $("#span-created").html(content_data['all_status']['created_count'] + '(' + content_data['all_status'][
      'created_percent'
    ] + '%)');
    $("#span-vacant").html(content_data['all_status']['vacant_count'] + '(' + content_data['all_status'][
      'vacant_percent'
    ] + '%)');
    $("#span-unavailable").html(content_data['all_status']['unavailable_count'] + '(' + content_data['all_status'][
      'unavailable_percent'
    ] + '%)');
    $("#span-dirty").html(content_data['all_status']['dirty_count'] + '(' + content_data['all_status'][
      'dirty_percent'
    ] + '%)');
  }
  function add_side_rooms(content_data) {
    $(".total-rooms").html("Total Rooms of current building : " + content_data['total_rooms']);
    $("#span-room-total").html(content_data['all_rooms_status']['total_count']);
    $("#span-room-occupied").html(content_data['all_rooms_status']['occupied_count'] + '(' + content_data['all_rooms_status'][
      'occupied_percent'
    ] + '%)');
    $("#span-room-vacant").html(content_data['all_rooms_status']['vacant_count'] + '(' + content_data['all_rooms_status'][
      'vacant_percent'
    ] + '%)');
    $("#span-room-unavailable").html(content_data['all_rooms_status']['unavailable_count'] + '(' + content_data['all_rooms_status'][
      'unavailable_percent'
    ] + '%)');
  }
  let isOver = false;
  let cur_tooltip;

  function add_tooltips(floor_data) {
    const propNames = Object.keys(floor_data);
    propNames.forEach((i) => {
      let each_floor = floor_data[i];
      for (j = 0; j < each_floor.length; j++) {
        var each_room = each_floor[j]['beds'];
        for (k = 0; k < each_room.length; k++) {
          add_tooltip(each_floor[j]["roomNo"], each_room[k]);
        }
      }
    });

    $(document).on("mouseover", ".tooltip", (event) => {
      isOver = true;
    });

    $(document).on("mouseleave", ".tooltip", (event) => {
      // cur_tooltip.tooltip('hide');
      // setTimeout(function () { $('#myLink').tooltip('hide'); }, 500);
    });

    $('[data-toggle="tooltip"]').on('mouseenter', function() {
      if (cur_tooltip != null && cur_tooltip[0] == $(this)[0]) {
        return;
      }
      if (cur_tooltip)
        cur_tooltip.tooltip('hide');
      $(this).tooltip('show');
      cur_tooltip = $(this);
      // setTimeout(function () {cur_tooltip.tooltip('hide'); }, 3000);
    });
  }

  function add_tooltip(room_name, bed_data) {
    let t_data = '<tr><th class="text-right">Bed&nbsp;</th><td >' + room_name + '-' + bed_data['bedName'] +
      '</td></tr><tr><th class="text-right">Status&nbsp;</th><td class="">' + bed_data['status'] + '</td></tr>';
    t_data += '<tr><th class="text-right" style="padding-right:5px;"></th><td>';
    t_data +=
      '<button class="btn btn-primary status_btn" style="padding: 0 18px!important;" data-toggle="modal" data-target="#change_status_dlg" data-backdrop="static" data-keyboard="false" data-bedid="' +
      bed_data['id'] + '" onclick="change_status(' + bed_data['id'] + ')">Change</button>';
    t_data += '</td></tr>';

    if (bed_data["user"] != null) {
      t_data += '<tr><th class="text-right">Name&nbsp;</th> <td><a href="' + bed_data["user"]["url"] +
        '" rel="noreferrer" target="_blank">' + bed_data["user"]["name"] + '</a></td> </tr>' +
        ' <tr> <th class="text-right" >Gender&nbsp;</th> <td>' + bed_data["user"]["gender"] + '</td> </tr>' +
        ' <tr> <th class="text-right" >Country&nbsp;</th> <td>' + bed_data["user"]["country"] + '</td> </tr>' +
        ' <tr> <th class="text-right" >Identifier&nbsp;</th> <td>' + bed_data["user"]['identifier'] + '</td> </tr>' +
        '<tr><th>Avatar </th><td>';
      if (bed_data["user"]["avatar"] == "") {
        t_data += '<img src="<?=url("");?>assets/images/avatar.png" class="user-avatar2"></td></tr>';
      } else {
        t_data += '<img src="<?=url("");?>uploads/avatar/' + bed_data["user"]["avatar"] +
          '" class="user-avatar2"></td></tr>';
      }
    }



    $('#popup-root tbody').html(t_data);
    $(".bed-" + bed_data['id']).tooltip({
      html: true,
      title: $('#popup-root').html(),
      placement: 'auto right',
      trigger: 'manual',
      delay: {
        hide: 400
      }
    });
  }
  </script>
  <script src="<?= url(""); ?>assets/js/room.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js">
  </script>

  <style>
  tbody,
  td,
  tfoot,
  th,
  thead,
  tr {
    border: 0 solid;
    border-color: inherit;
  }

  .table>thead>tr>th,
  .table>tbody>tr>th,
  .table>tfoot>tr>th,
  .table>thead>tr>td,
  .table>tbody>tr>td,
  .table>tfoot>tr>td {
    border-top: inherit;
  }
  </style>
</body>

</html>