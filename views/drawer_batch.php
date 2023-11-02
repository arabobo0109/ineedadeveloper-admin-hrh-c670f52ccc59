<?php include "includes/head.php" ?>
<body>
    {{ view("includes/header", $data); }}
    {{ view("includes/sidebar", $data); }}
    <div class="content">
        <div class="page-title">
            <div class="pull-right page-actions lower">
                <button class="btn btn-primary"
                    @if ($user->old_batch>0)
                        onclick='swal("You have already opened batch!", "Please close your batch (Drawer number is {{$user->old_batch}}).", "error")'>
                    @else
                        data-toggle="modal" data-target="#create_batch" data-backdrop="static">
                    @endif
                    <i class="ion-plus-round"></i> Open New Batch
                </button>
                @if (!env("SITE_Portal"))
                <button class="btn btn-primary" data-toggle="modal" data-target="#close_batch"><i class="ion-plus-round"></i>Insert Closing Amount</button>
                @endif
            </div>
            <h3>Drawer Batch</h3>
            <p>All batch lists.</p>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="light-card table-responsive p-b-5em">
                    <table class="table display companies-list" id="data-table">
                        <thead>
                            <tr>
                                <th class="text-center w-70"></th>
                                <th>Drawer Number</th>
                                <th>Open Amount</th>
                                <th>Closing Amount</th>
                                <th>Difference</th>
                                <th>Credit</th>
                                <th>Start time</th>
                                <th>End time</th>
                                <th>Owner</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ( count($batchs) > 0 )
                            @foreach ( $batchs as $index => $batch )
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td><a href="<?=url("Drawer@viewTransaction");?>{{ $batch->id }}" >
                                    <strong>{{ $batch->drawer_number }}</strong>
                                    </a>
                                </td>
                                <td><strong>{{ $batch->open_amount }}</strong> </td>
                                <td><strong>{{ $batch->closing_amount }}</strong> </td>
                                <td><strong>{{ $batch->difference }}</strong> </td>
                                <td><strong>{{ $batch->total_credit }}</strong> </td>
                                <td><strong>{{ $batch->start_time }}</strong> </td>
                                <td><strong>{{ $batch->end_time }}</strong> </td>
                                @if ($batch->user_id==1)
                                    <td><span class="label label-success">Daily</span></td>
                                    <td class="text-center"><strong>{{ $batch->status }}</strong> </td>
                                @else
                                    <td><strong>{{ $batch->owner }}</strong> </td>
                                    <td class="text-center">
                                        @if ($batch->status=='open')
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#close_batch_portal" data-batch-id="{{ $batch->id }}">Close batch</button>
                                        @else
                                            closed
                                        @endif
                                    </td>
                                @endif

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

    <!--Create batch-->
    <div class="modal fade" id="create_batch" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create New Batch</h4>
                </div>
                <form class="simcy-form" action="<?=url("Drawer@create");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
	                            <div class="col-md-6">
	                                <label>Open amount of new batch</label>
	                                <input type="number" class="form-control" placeholder="Open Amount" data-parsley-required="true" name="open_amount" value="0">

	                            </div>
                                <div class="col-md-6">
                                    <label>CC Terminal Device</label> <label class="color-red">*</label>
                                    <select class="form-control" name="season" data-parsley-required="true">
                                        <option value="none">None</option>
                                        @foreach ($terminals as $terminal )
                                        <option value="{{ $terminal->device_id }}" {{$user->season == $terminal->device_id?'selected':''}}>{{ $terminal->nickname }}</option>
                                        @endforeach
                                    </select>
                                </div>
	                        </div>
	                    </div>
                    </div>
                    <div class="modal-footer">                    	
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Batch</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Close batch Modal -->
    <div class="modal fade" id="close_batch" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Insert closing amount</h4>
                </div>
                <form class="simcy-form" action="<?=url("Drawer@insertCloseAmount");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>The batch will be closed at midnight automatically</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Closing Amount</label>
                                    <input type="number" class="form-control" name="closing_amount" placeholder="ClosingAmount" data-parsley-required="true">
                                    <input type="hidden" name="csrf-token" value="{{ csrf_token(); }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Close batch</button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <!-- Close batch Portal -->
    <div class="modal fade" id="close_batch_portal" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Close the batch</h4>
                </div>
                <form class="simcy-form" action="<?=url("Drawer@close");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf-token" value="{{ csrf_token(); }}" />
                    <input type="hidden" name="batch_id" value="0" />
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Closing amount of the batch</label>
                                    <input type="number" class="form-control" name="closing_amount" placeholder="ClosingAmount" data-parsley-required="true">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Close batch</button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <!-- footer -->
    {{ view("includes/footer"); }}

    @if ( count($batchs) > 0 )
    <script>
        $(document).ready(function() {
            $('#data-table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    // 'print'
                //     'excelHtml5'
                ]
            });
        });

        //triggered when modal is about to be shown
        $('#close_batch_portal').on('show.bs.modal', function(e) {

            //get data-id attribute of the clicked element
            let batch_id = $(e.relatedTarget).data('batch-id');
            //populate the textbox
            $(e.currentTarget).find('input[name="batch_id"]').val(batch_id);
        });
    </script>
    @endif
</body>

</html>
