<?php include "includes/head.php" ?>

<body>

    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}

    <div class="content">
        <div class="page-title">
            <div class="row student">
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create"
                            data-backdrop="static" data-keyboard="false"><i class="ion-plus-round"></i> New
                        Student
                    </button>
                </div>
                <div class="col-md-3">
                    <form method="post"
                          action="<?= url("Import@Excel"); ?>" >
                        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">
                        <button class="btn btn-success" name="action" value="export"><i class="ion-code-download"></i>
                            Export to Excel File
                        </button>
                    </form>
                </div>

                <div class="col-md-3">
                    <button class="btn btn-success" data-toggle="modal" data-target="#send-email" data-backdrop="static" data-keyboard="false">Send Email to all students</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs">
                    <li><a href="<?=url("");?>students/" @if (!isset($_GET['status']))  class="active" @endif>Current</a></li>
                    <li><a href="<?=url("");?>students/?status=Terminated"@if (isset($_GET['status']) && $_GET['status'] == "Terminated")  class="active" @endif>Terminated</a></li>
                </ul>
                <div class="light-card table-responsive p-b-3em">
                    <div class="custom-filter" style="display: none">
                        <label>
                            Display Per Page:
                            <select id="per-page-select">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="50">50</option>
                            </select>
                        </label>
                        <label style="margin-left: 20px">
                            Sign:
                            <select id="action-select">
                                <option value="">All</option>
                                <option value='Pending'>Pending</option>
                                <option value='Sent'>Sent</option>
                                <option value='Signed'>Signed</option>
                                <option value='Lease on File'>Lease on File</option>
                            </select>
                        </label>
                        <label style="margin-left: 20px">
                            Status:
                            <select id="status-select">
                                <option value="">All</option>
                                <option value='Created'>Created</option>
                                <option value='Arrived'>Arrived</option>
                                <option value='Extended'>Extended</option>
                                <option value='Terminated'>Terminated</option>
                            </select>
                        </label>
                        <label style="margin-left: 20px">
                            Season:
                            <select id="season-select">
                                <option value="">All</option>
                                <option value='Autumn 2022'>Autumn 2022</option>
                                <option value='Spring 2024'>Spring 2024</option>
                                <option value='Spring 2023'>Spring 2023</option>
                                <option value='Summer 2023'>Summer 2023</option>
                            </select>
                        </label>
                    </div>

                    <table class="table display companies-list" id="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th class="text-center w-70">Photo</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Country</th>
                                <th>Employer</th>
                                <th>Balance owed</th>
                                <th>Holding</th>
                                <th>Anticipated Start</th>
                                <th>Anticipated End</th>
                                <th class="text-center">Bed Name</th>
                                <th class="text-center">Season</th>
                                <th class="text-center">Sign</th>
                                <th class="text-center">Status</th>
                                <th class="text-center w-50">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ( count($customers) > 0 )
                            @foreach ( $customers as $customer )
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td class="text-center">
                                    @if ( !empty($customer->avatar) )
                                    <img src="<?=url("")?>uploads/avatar/{{ $customer->avatar }}" class="img-responsive img-circle table-avatar">
                                    @else
                                    <img src="<?=url("")?>assets/images/avatar.png" class="img-responsive table-avatar">
                                    @endif
                                </td>
                                <td>
                                    <a href="<?=url("Customer@profile");?>{{$customer->id}}">
                                    <strong>{{ $customer->fname }} {{ $customer->lname }}</strong>
                                        @if ( $customer->account_type == "Test" )
                                        <span class="label label-info">Test</span>
                                        @endif
                                    </a>
                                </td>
                                <td><strong>{{ $customer->email }}</strong></td>
                                <td>{{ $customer->gender }}</td>
                                <td>{{ $customer->country }}</td>
                                <td>{{ $customer->employer_name }}</td>
                                <td><label class="color-red">${{ $customer->balance }}</label></td>
                                <td><label class="color-red">${{ $customer->holding_balance }}</label></td>
                                <td>{{ $customer->lease_start }}</label></td>
                                <td>{{ $customer->lease_end }}</label></td>
                                <td class="text-center"><strong>{{ $customer->unit}}</strong></td>
                                <td>{{ $customer->season }}</td>
                                <td class="text-center">
                                    <span class="label
                                    @if ( $customer->sign_status == 'Pending' )
                                        label-warning
                                    @elseif ( $customer->sign_status == 'Sent' )
                                        label-info
                                    @else
                                        label-success
                                    @endif
                                    ">
                                        {{ $customer->sign_status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="label
                                    @if ( $customer->status == 'Created' )
                                        label-warning
                                    @elseif ( $customer->status == 'Arrived' )
                                        label-success
                                    @elseif ( $customer->status == 'Extended' )
                                        label-info
                                    @else
                                        label-danger
                                    @endif
                                    ">
                                        {{ $customer->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <span class="company-action dropdown-toggle" data-toggle="dropdown"><i class="ion-ios-more"></i></span>
                                        <ul class="dropdown-menu" role="menu">
                                            <li role="presentation">
                                                @if($customer->status != 'Terminated')
                                                    <a class="fetch-display-click" data="customerid:{{ $customer->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Customer@assign_room");?>" holder=".update-holder-room" modal="#room" href="">Assign Room</a>
                                                    @if($customer->status == 'Arrived')
                                                        <a class="send-to-server-click"  data="customerid:{{ $customer->id }}|status:Created|csrf-token:{{ csrf_token() }}" url="<?=url("Customer@changeStatus");?>" warning-title="Are you sure?" warning-message="This student's status will be changed." warning-button="Continue" loader="true" href="">reinstate Created</a>
                                                    @endif

                                                    <a class="send-to-server-click"  data="customerid:{{ $customer->id }}|status:Paused|csrf-token:{{ csrf_token() }}" url="<?=url("Customer@changeStatus");?>" warning-title="Are you sure?" warning-message="This student's status will be paused." warning-button="Continue" loader="true" href="">Set Pause</a>

                                                    <a class="fetch-display-click" data="customerid:{{ $customer->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Customer@cancel_lease");?>" holder=".holder-cancel-lease" modal="#cancel_lease_modal" href="">Cancel Lease</a>

                                                @else

                                                    <a class="send-to-server-click"  data="customerid:{{ $customer->id }}|status:Created|csrf-token:{{ csrf_token() }}" url="<?=url("Customer@changeStatus");?>" warning-title="Are you sure?" warning-message="This student's status will be changed." warning-button="Continue" loader="true" href="">reinstate Created</a>

                                                    <a class="send-to-server-click"  data="customerid:{{ $customer->id }}|status:Arrived|csrf-token:{{ csrf_token() }}" url="<?=url("Customer@changeStatus");?>" warning-title="Are you sure?" warning-message="This student's status will be changed." warning-button="Continue" loader="true" href="">reinstate Arrived</a>

                                                @endif
                                                <a class="send-to-server-click"  data="customerid:{{ $customer->id }}|status:Suspended|csrf-token:{{ csrf_token() }}" url="<?=url("Customer@changeStatus");?>" warning-title="Are you sure?" warning-message="This student's data will be suspended." warning-button="Continue" loader="true" href="">Suspend</a>

<!--                                                <a class="send-to-server-click"  data="customerid:{{ $customer->id }}|csrf-token:{{ csrf_token() }}" url="--><?//=url("Customer@delete");?><!--" warning-title="Are you sure?" warning-message="This student's data will be deleted." warning-button="Continue" loader="true" href="">Delete</a>-->

                                                <a class="send-to-server-click"  data="customerid:{{ $customer->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Customer@makeTester");?>" warning-title="Are you sure?" warning-message="This student will be tester." warning-button="Continue" loader="true" href="">Make Tester</a>
                                            </li>

                                        </ul>
                                    </div>
                                </td>
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
    </div>

    <!--Create User Account-->
    <div class="modal fade" id="create" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Student</h4>
                </div>
                <form class="simcy-form" id="create-customer-form" action="<?=url("Customer@create");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <?php include 'student_details.php'?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Cont to Room Assignment</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!--Room Assign dialog-->
    <div class="modal fade" id="room" role="dialog" style="background-color: #fff;">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Room Assignment</h4>
                </div>
                <form class="update-holder-room simcy-form" action="<?=url("Customer@updateRoom");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box"><div class="circle-loader"></div></div>
                </form>
            </div>
        </div>
    </div>

    <!--Cancel Lease dialog-->
    <?php include "customer/modal_cancel_lease.php" ?>
    <!-- footer -->
    {{ view("includes/footer"); }}

    <?php include 'customer/sendMail.php'?>

    <link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
    <script src="<?=url("");?>assets/js/jquery-ui.js"></script>

    @if ( count($customers) > 0 )
    <script>
        $(document).ready(function() {

            $('#data-table').DataTable({
                order: [[0, 'desc']],
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    'pdfHtml5',
                ]
            });

            $(".dataTables_filter").append($(".custom-filter"));
            $(".custom-filter").show();

            $('#season-select').on('change',function(){
                $('#data-table').DataTable().search(
                    $('#season-select').val()
                ).draw();
            });

            $('#status-select').on('change',function(){
                $('#data-table').DataTable().search(
                    $('#status-select').val()
                ).draw();
            });

            $('#action-select').on('change',function(){
                $('#data-table').DataTable().search(
                    $('#action-select').val()
                ).draw();
            });

            $('#per-page-select').on('change',function(){
                var selectedValue = $(this).val();
                $('#data-table').DataTable().page.len(selectedValue).draw();;
            });
	        
	        $("[id='print_lease']").change(function(){				
	            if($(this).is(':checked')){
	            	var student_lease_email = "student{{($customers[0]->id)+1}}@irhliving.com";
					$("[name='print_lease']").val(student_lease_email);
					$("[name='email']").val(student_lease_email);
					$("[name='email']").prop( "disabled", true );
					$("[name='email']").attr( "data-parsley-required", "false" );
					// swal("Print Lease!", "The email is " + student_lease_email, "success");
				}
				else{
                    $("[name='email']").val('');
					$("[name='email']").prop( "disabled", false );
					$("[name='email']").attr( "data-parsley-required", "true" );
				}
	        });

            $("[id='weekly_rate_check']").change(function(){
                if($(this).is(':checked')){
                    $("[id='weekly_rate']").show();
                }
                else{
                    $("[id='weekly_rate']").hide();
                }
            });
	        
	        $('.select-employer').change(function() {
			    if ($(this).val() == 'other') {
                    $('#create_employer').collapse('show');
			    }
			});
			
			$( "#save_employer" ).click(function() {
				$.ajax({
					url: baseUrl + 'students/create_employer',
					type: "post",
					data: {
						name : $( "#employer_name" ).val(),
						company_info : $( "#company_info" ).val(),
						email : $( "#employer_email" ).val(),
						"csrf-token": Cookies.get("CSRF-TOKEN")
					},
					success: function(data){
						swal("Employer!", "New Employer Created", "success");
						$(".select-employer").find('option').remove();
                        $(".select-employer").append(data);
                        $('#create_employer').collapse('hide');
					}
				});
			});

            $( ".compare_sec_input" ).keyup(function() {
                var company_security_deposit = {{$company_security}};
                if(company_security_deposit == $( ".compare_sec_input" ).val())
                    $( ".compare_sec_label" ).hide();
                else
                    $( ".compare_sec_label" ).show();
            });

            $( ".compare_weekly_input" ).keyup(function() {
                var company_weely_rate = {{$company_weekly}};
                if(company_weely_rate == $( ".compare_weekly_input" ).val())
                    $( ".compare_weekly_label" ).hide();
                else
                    $( ".compare_weekly_label" ).show();
            });
			            
        });
        let baseUrl = '<?=url("");?>';
        let csrf='<?=csrf_token();?>';
    </script>
    @endif

    <script src="<?= url(""); ?>assets/js/room.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            $(".input-daterange").datepicker({
                startDate: new Date(),
                todayHighlight: !0,
                format: 'yyyy-mm-dd'
            });

            $("[name='birthday']").datepicker({
                todayHighlight: !0,
                format: 'yyyy-mm-dd'
            });

            $('input[name="lname"]').on('blur', function() {
                var firstName = $('input[name="fname"]').val();
                var lastName = $(this).val();

                // Check if firstName is null or empty
                if (!firstName || !lastName) {
                    return; // Don't proceed with the AJAX request
                }

                $.ajax({
                    url: baseUrl + 'students/check_names',
                    type: "post",
                    data: {
                        fname: firstName,
                        lname: lastName,
                        "csrf-token": Cookies.get("CSRF-TOKEN")
                    },
                    success: function(response){
                        if (response){
                            let message=response.email+' '+response.status+' in '+response.country;
                            let flag=(response.flag==null)?"":response.flag;
                            swal("Same Name Existed! "+flag, message, 'info')
                        }
                        // Process the response from the server
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            });

            $('input[name="email"]').on('blur', function() {
                var email = $(this).val();
                if (!email) {
                    return; // Don't proceed with the AJAX request
                }

                $.ajax({
                    url: baseUrl + 'students/check_email',
                    type: "post",
                    data: {
                        email: email,
                        "csrf-token": Cookies.get("CSRF-TOKEN")
                    },
                    success: function(response){
                        if (response){
                            let message=response.email+' '+response.fname+' '+response.lname+' '+response.status+' in '+response.country;
                            let flag=(response.flag==null)?"":response.flag;
                            swal("Same Email Existed! "+flag, message, 'info')
                        }
                        // Process the response from the server
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            });
        });

    </script>
</body>

</html>
