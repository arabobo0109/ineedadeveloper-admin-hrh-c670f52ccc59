<?php include "includes/head.php" ?>
<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}
    <div class="content">
        <div class="page-title">
            <div class="pull-right page-actions lower">
                <button class="btn btn-primary" data-toggle="modal" data-target="#create_fine_fee" data-backdrop="static" data-keyboard="false"><i class="ion-plus-round"></i> New Fine fee</button>
            </div>
            <h3>Fine fees</h3>
            <p>All Fine fee lists.</p>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="light-card table-responsive p-b-3em">
                    <table class="table display companies-list" id="data-table">
                        <thead>
                            <tr>
                                <th class="text-center w-70"></th>
                                <th>Fine Type</th>
                                <th>Fine Amount</th>
                                <th class="text-center w-70">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ( count($fine_fees) > 0 )
                            @foreach ( $fine_fees as $index => $fine_fee )
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td><strong>{{ $fine_fee->type }}</strong> </td>
                                <td><strong>{{ $fine_fee->amount }}</strong> </td>

                                <td class="text-center">
                                    <div class="dropdown">
                                        <span class="company-action dropdown-toggle" data-toggle="dropdown"><i class="ion-ios-more"></i></span>
                                        <ul class="dropdown-menu" role="menu">
                                            <li role="presentation">
                                                <a class="fetch-display-click" data="fineid:{{ $fine_fee->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Fine@updateview");?>" holder=".update-fine-form" modal="#update" href="">Edit</a>
                                                <a class="send-to-server-click"  data="fineid:{{ $fine_fee->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Fine@delete");?>" warning-title="Are you sure?" warning-message="This fine fee will be deleted." warning-button="Continue" loader="true" href="">Delete</a>
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

    <!--Create Fine Fee-->
    <div class="modal fade" id="create_fine_fee" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Fine</h4>
                </div>
                <form class="simcy-form" id="create-user-form" action="<?=url("Fine@create");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Fill in Fine's details</p>
                        <div class="form-group">
	                        <div class="row">
	                            <div class="col-md-12 ">
	                                <label>Fine type</label>
	                                <input type="text" class="form-control" id="fine_type" placeholder="Fine type" data-parsley-required="true" name="type">
	                                <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
	                            </div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <div class="row">
	                            <div class="col-md-12">
	                                <label>Fine Amount($)</label>
	                                <input type="number" class="form-control" id="fine_amount" placeholder="Fine Amount" data-parsley-required="true" name="amount">
	                            </div>
	                        </div>
	                    </div>
	                    
                    </div>
                    <div class="modal-footer">                    	
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Fine</button>
                    </div>
                </form>
            </div>

        </div>
    </div>


    <!-- Update Fine Modal -->
    <div class="modal fade" id="update" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Fine Fee</h4>
                </div>
                <form class="update-fine-form simcy-form" action="<?=url("Fine@updateFine");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box"><div class="circle-loader"></div></div>
                </form>
            </div>

        </div>
    </div>

    <!-- footer -->
    {{ view("includes/footer"); }}

    @if ( count($fine_fees) > 0 )
    <script>
        $(document).ready(function() {
            $('#data-table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5'
                ]
            });
            
            
        });
    </script>
    @endif

</body>

</html>
