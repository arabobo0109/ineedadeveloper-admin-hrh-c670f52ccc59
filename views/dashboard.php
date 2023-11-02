<?php include "includes/head.php" ?>

<style>
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

<body>
    
    {{ view("includes/header", $data); }}
    {{ view("includes/sidebar", $data); }}
    <!-- bg-white -->

    <div class="content">
        @if($user->role=="superadmin")
        <div class="row bg-white"  style="margin-top: 25px;">
            <div class="navbar_section" style="padding-top: 30px;padding-left: 30px;">
                <ul class="nav nav-pills building-tab-area">
                </ul>
            </div>
            <div class="col-md-12" style="background: #fff;margin-top: 20px;">
                <aside class="col-md-6 d-flex" style="margin-top: 20px;">
                    <div class="d-flex flex-column w-100">
                        <div class="card col-md-3" style="width: max-content;">
                            <?php include "room/global_donut_chart.php" ?>
                        </div>
                    </div>
                </aside>

                <aside class="col-md-6 d-flex" style="margin-top: 20px;">
                    <div class="d-flex flex-column w-100">
                        <div class="card col-md-3" style="width: max-content;">
                            {{ view("room/donut_chart", ['title' => "Current building Status", 'id' => 'svg']); }}
                        </div>
                    </div>
                </aside>
            </div>

            <div class="col-md-12">
                <div class="light-card table-responsive p-b-3em" style="margin-top: 30px;margin-bottom: 30px;">
                    <table class="table display companies-list" id="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Company Name</th>
                                <th>Weekly Fee</th>
                                <th>Deposit Fee</th>
                                <th>Administration</th>
                                <th>Laundry</th>
                                <th>Number of Redsidents</th>
                                <th>Residents' Balance Owed</th>
                                <th>Residents' Holding</th>
                                <th style="color: red;">Error Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ( count($companies) > 0 )
                            @foreach ( $companies as $company )
                            <tr>
                                <td>{{ $company->id }}</td>
                                <td><strong>{{ $company->name }}</strong><br></td>
                                <td><strong>${{ $company->weekly }} </strong></td>
                                <td><strong>${{ $company->security }} </strong></td>
                                <td><strong>${{ $company->administration }} </strong></td>
                                <td><strong>${{ $company->laundry }} </strong></td>
                                <td><strong>{{ $finance['num'][$company->id] }}</strong></td>
                                <td><strong>${{ $finance['owed'][$company->id] }}</strong></td>
                                <td><strong>${{ $finance['holding'][$company->id] }}</strong></td>
                                <td><strong>$</strong></td>


                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="9" class="text-center">It's empty here</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @endif
        <div class="row">
            @if ( $user->role == "user" )
            <div style="padding-left: 18px;">
                <?php include "customer/dashboard_student.php" ?>
            </div>
            @endif
        </div>
    </div>

    <!-- footer -->
    {{ view("includes/footer"); }}

    <script src="<?= url(""); ?>assets/libs/jquery-ui/jquery-ui.min.js"></script>

    <script src="<?= url(""); ?>assets/libs/clipboard/clipboard.min.js"></script>
    <script src="<?= url(""); ?>assets/libs/knob/jquery.knob.min.js"></script>
    <script src="<?= url(""); ?>assets/js/jquery.slimscroll.min.js"></script>
    <!--    <script src="--><? //=url("");
                            ?><!--assets/js/echarts.min.js"></script>-->
    <script src="<?= url(""); ?>assets/js/simcify.min.js"></script>


    <!--<script src="<?/*=url("");*/ ?>assets/js/auth.js"></script>-->
    <!--    <script src="--><? //=url("");
                            ?><!--assets/js/files.js"></script>-->

    <script src='https://cdnjs.cloudflare.com/ajax/libs/snap.svg/0.3.0/snap.svg-min.js'></script>
    <script src="<?= url(""); ?>assets/js/svg-donut-chart-framework.js"></script>
    <script>
        $(function() {
            $(".dial").knob();
        });
        $('.meter-widget').width($('.col-md-6').width());

        var dom = document.getElementById("meter");
        var app = {};
    </script>

    <script>
        let baseUrl = '<?= url(""); ?>';
        let csrf = '<?= csrf_token(); ?>';
        let building_id = '<?= input('building'); ?>';

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
                    drawChart(data.draw_status);
                    draw_global_chart(data.all_status);
                    // drawChart(data.draw_status, '#global_svg');

                    // const floor_data = data['floors'];
                    // add_floor_content(floor_data);
                    add_side_beds(data);
                    // add_tooltips(floor_data);
                }
            });
        }

        function add_building_tab() {

            const buildings = <?= json_encode($data['buildings']) ?>;

            console.log(buildings);
            let temp_cont = '';
            buildings.forEach(function(building) {
                let isActive = (building.id == building_id) ? 'active' : '';
                temp_cont += '<li class="nav-item"> <a class="nav-link nav_button ' + isActive + '" href="?building=' + building.id + '" aria-current="page">' + building.name + '</a> </li>';
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

                floor_str += '<td><table class="table table-borderless"><tbody><tr><td><h2>' + floors_prefix[i] + ' Floor</h2> </td> </tr><tr> <td> <table class="table floor_table" data-table="Floor table"> <tbody> <tr> <td class="room-type-td-others" colspan="2">STAIR</td> </tr>';

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
                        room_str += '<tr><td class="room-type-td-room"><table class="table mb-0 bed_table" data-table="Beds Table"><tbody> <tr>';
                        room_str += ' <td class="w_room p-1">' + each_floor[j]["roomNo"] + '</td>';
                        room_str += bed_str;
                        room_str += ' </tr> </tbody> </table></td>';
                    } else {
                        room_str += '<td class="room-type-td-room"><table class="table mb-0 bed_table" data-table="Beds Table"><tbody> <tr>';
                        room_str += bed_str;
                        room_str += ' <td class="w_room p-1">' + each_floor[j]["roomNo"] + '</td>';
                        room_str += ' </tr> </tbody> </table></td></tr>';
                    }

                    left_right_position ? (left_right_position = false) : (left_right_position = true);
                }

                floor_str += room_str;

                floor_str += '</tr> <tr> <td class="room-type-td-others" colspan="2">STAIR</td> </tr> </tbody> </table> </td> <td width="20"></td> </tr></tbody> </table> </td>';
                $(".floor-content-area").html(floor_str);
            });
        }

        function draw_global_chart(content_data) {
            let chart_data = [{
                    value: content_data.created_percent == 0 ? 0.01 : content_data.created_percent,
                    label: 'Created ' + content_data.created_count + '(' + content_data.created_percent + '%)',
                    color: 'purple'
                },
                {
                    value: content_data.occupied_percent == 100 ? 99.99 : content_data.occupied_percent,
                    label: 'Occupancy ' + content_data.occupied_count + '(' + content_data.occupied_percent + '%)',
                    color: '#d9b300'
                },
                {
                    value: content_data.vacant_percent == 100 ? 99.99 : content_data.vacant_percent,
                    label: 'Vacant ' + content_data.vacant_count + '(' + content_data.vacant_percent + '%)',
                    color: '#118dff'
                },
                {
                    value: content_data.unavailable_percent == 100 ? 99.99 : content_data.unavailable_percent,
                    label: 'Unavailable ' + content_data.unavailable_count + '(' + content_data.unavailable_percent + '%)',
                    color: '#5F220E'
                },
                {
                    value: content_data.dirty_percent == 100 ? 99.99 : content_data.dirty_percent,
                    label: 'Dirty ' + content_data.dirty_count + '(' + content_data.dirty_percent + '%)',
                    color: '#240000'
                },
            ];
            var donut = $("#global_svg").donut({
                donutSize: donutSize,
                center: {
                    x: donutSize / 2,
                    y: donutSize / 2
                },
                radius: donutSize * 0.3 / 1,
                data: chart_data
            });

            donut.startShowAnimation(function() {
                $('button').attr('disabled', false);
            });
        }

        function add_side_beds(content_data) {


            $(".total-beds").html("Total Beds of current building : " + content_data['total_beds']);
            $("#span-total").html(content_data['all_status']['total_count']);
            $("#span-occupied").html(content_data['all_status']['occupied_count'] + '(' + content_data['all_status']['occupied_percent'] + '%)');
            $("#span-created").html(content_data['all_status']['created_count'] + '(' + content_data['all_status']['created_percent'] + '%)');
            $("#span-vacant").html(content_data['all_status']['vacant_count'] + '(' + content_data['all_status']['vacant_percent'] + '%)');
            $("#span-unavailable").html(content_data['all_status']['unavailable_count'] + '(' + content_data['all_status']['unavailable_percent'] + '%)');
            $("#span-dirty").html(content_data['all_status']['dirty_count'] + '(' + content_data['all_status']['dirty_percent'] + '%)');
        }

        
    </script>
    <script src="<?= url(""); ?>assets/js/room.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

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