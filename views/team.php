<?php include "includes/head.php" ?>
<body>
    {{ view("includes/header", $data); }}

    {{ view("includes/sidebar", $data); }}
    
    <div class="content">
        <div class="page-title">
            <div class="pull-right page-actions lower">
                <button class="btn btn-primary" data-toggle="modal" data-target="#create" data-backdrop="static" data-keyboard="false"><i class="ion-plus-round"></i> Add Staff</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="light-card table-responsive p-b-3em">
                    <table class="table display companies-list" id="data-table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>CC Terminal</th>
                            <th>Status</th>
                            <th class="text-center w-70">Action</th>
                        </tr>
                        </thead>
                        <tbody>
            @if ( count($team) > 0 )
            @foreach ( $team as $team )
            <tr>
                <td>{{ $team->id }}</td>
                <td>
                        <strong>{{ $team->fname }} {{ $team->lname }}</strong>
                </td>
                <td><strong>{{ $team->email }}</strong></td>
                <td>
                    <span class="label
                                    @if ( $team->role == 'admin' )
                                        label-success
                                    @else
                                        label-info
                                    @endif
                                    ">
                        {{ $team->role }}
                    </span>
                </td>
                <td>{{ $team->terminal }}</td>
                <td>
                    <span class="label
                                    @if ( $team->action == 'Active' )
                                        label-success
                                    @else
                                        label-warning
                                    @endif
                                    ">
                        {{ $team->action }}
                    </span></td>
                <td class="text-center">
                    <div class="dropdown">
                        <span class="company-action dropdown-toggle" data-toggle="dropdown"><i class="ion-ios-more"></i></span>
                        <ul class="dropdown-menu" role="menu">
                            <li role="presentation">
                                <a class="fetch-display-click" data="memberid:{{ $team->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Team@updateview");?>" holder=".update-holder" modal="#update" href="">Edit</a>
                                @if($team->action == 'Active')
                                <a class="send-to-server-click"  data="user_id:{{ $team->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Team@changeStatus");?>" warning-title="Are you sure?" warning-message="This member status will be disabled." warning-button="Continue" loader="true" href="">Disable</a>
                                @else
                                <a class="send-to-server-click"  data="user_id:{{ $team->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Team@changeStatus");?>" warning-title="Are you sure?" warning-message="This member status will be enabled." warning-button="Continue" loader="true" href="">Enable</a>
                                @endif
                                <a class="send-to-server-click"  data="memberid:{{ $team->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Team@delete");?>" warning-title="Are you sure?" warning-message="This member's profile and data will be deleted." warning-button="Continue" loader="true" href="">Delete</a>
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

    <div class="modal fade" id="create" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Staff Account</h4>
                </div>
                <form class="simcy-form" id="create-team-form" action="<?=url("Team@create");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Fill in staff's details, an email with login details will be sent to member.</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 ">
                                    <label>First name</label>
                                    <input type="text" class="form-control" name="fname" placeholder="First name" data-parsley-required="true">
                                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                                </div>
                                <div class="col-md-6">
                                    <label>Last name</label>
                                    <input type="text" class="form-control" name="lname" placeholder="Last name" data-parsley-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Email address</label>
                                    <input type="email" class="form-control" name="email" placeholder="Email address" data-parsley-required="true">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Account</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Update Team Member Modal -->
    <div class="modal fade" id="update" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Staff Account </h4>
                </div>
                <form class="update-holder simcy-form" id="update-team-form" action="<?=url("Team@update");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box"><div class="circle-loader"></div></div>
                </form>
            </div>

        </div>
    </div>

    <!-- footer -->
    {{ view("includes/footer"); }}

</body>

<script>
    $(document).ready(function() {
        $('#data-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5'
            ]
        });
    });
</script>

</html>
