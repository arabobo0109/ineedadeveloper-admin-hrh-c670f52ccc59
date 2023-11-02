<?php include "includes/head.php" ?>
<body>
    {{ view("includes/header", $data); }}
    {{ view("includes/sidebar", $data); }}
    <div class="content">
        @if (isset($student))
        <?php include "customer/profile_header.php" ?>
        @endif
        <div class="page-title">
            @if ($user->role=='user' || isset($student))
            <div class="pull-right page-actions lower">
                <button class="btn btn-primary" data-toggle="modal" data-target="#create_request_dlg" data-backdrop="static" data-keyboard="false"><i class="ion-plus-round"></i> New Request</button>
            </div>
            @endif
            <h3>Maintenance Request List</h3>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="light-card table-responsive p-b-3em">
                    <table class="table display" id="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Unit</th>
                                <th>Requester Name</th>
                                <th>Content</th>
                                <th>Permission</th>
                                <th>Created at</th>
                                <th>Preferred time</th>
                                <th>Status</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ( count($list) > 0 )
                            @foreach ( $list as $item )
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->unit }}</td>
                                <td>{{ $item->requester_name }}</td>
                                <td>{{ $item->content }}</td>
                                <td>{{ $item->permission }}</td>
                                <td>{{ date('Y-m-d H:i',strtotime($item->created_at)) }}</td>
                                <td>{{ $item->preferred_time }}</td>
                                <td>{{ $item->status }}</td>
                                <td><a class="fetch-display-click btn btn-primary" data="id:{{ $item->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Maintenance@updateview");?>" holder=".update-holder" modal="#update" href="">View</a>
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

    <div class="modal fade" id="create_request_dlg" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create A New Maintenance Request</h4>
                </div>
                <form class="simcy-form" id="create-user-form" action="<?=url("Maintenance@create");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="student_id" value="{{ $student->id }}" />
                    <div class="modal-body">
                        <div class="form-group">
	                        <div class="row">
	                            <div class="col-md-12">
                                    <p>Tell us about the issue you are having. Please use as much detail as possible to help us resolve this more quickly. <label class="color-red">*</label></p>
                                    <textarea class="form-control" name="content" data-parsley-required="true" rows="5" ></textarea>
	                            </div>
	                        </div>
	                    </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="file" name="maintenance_image" class="croppie" accept="image/*" crop-width="1000" crop-height="700" >
                                </div>
                            </div>
                        </div>
	                    <p>To resolve the issue as quickly as possible, do we have permission to enter the residence if no one is there?<label class="color-red">*</label></p>
	                    <div class="form-group">
	                        <div class="row">
	                            <div class="col-md-12">
                                    <label class="radio"><input type="radio" name="permission" value="Yes" checked><span class="outer"><span class="inner"></span></span>Yes</label>
	                            </div>
	                        </div>
	                        <div class="row">
	                            <div class="col-md-12">
                                    <label class="radio"><input type="radio" name="permission" value="No" ><span class="outer"><span class="inner"></span></span>No</label>
	                            </div>
	                        </div>
	                        <div class="row">
	                            <div class="col-md-12">
                                    <label class="radio"><input type="radio" name="permission" value="N/A" ><span class="outer"><span class="inner"></span></span>N/A - Entry not necessary</label>
	                            </div>
	                        </div>
	                    </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-control" style="border: none">preferred time to fix :</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="preferred_time" autocomplete="off" placeholder="preferred time to fix">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Maintenance Request</h4>
                </div>
                <form class="update-holder simcy-form" action="<?=url("Maintenance@update");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box"><div class="circle-loader"></div></div>
                </form>
            </div>

        </div>
    </div>

    <!-- footer -->
    {{ view("includes/footer"); }}
    @if ( count($list) > 0 )
    <script>
        $(document).ready(function() {
            $('#data-table').DataTable({
                order: [[0, 'desc']],
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5'
                ]
            });
        });
    </script>
    @endif
</body>

</html>
