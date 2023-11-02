<!-- Edit User Account-->
<div class="modal fade" id="edit" role="dialog">
    <div class="close-modal" data-dismiss="modal">&times;</div>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Profile</h4>
            </div>
            <form class="simcy-form" id="edit-customer-form" action="<?=url("Student@updateDashboardUser");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="customerid" value="{{ $student->id }}">
                <div class="modal-body">
                    <?php include 'student_edit.php'?>
                    <div class="form-group">
                        <div class="row">
                            @if($student->status == 'Created')
                            <div class="col-md-6">
                                <label>Anticipated Start</label>
                                <input type="text" class="form-control" name="lease_start" value="{{ $student->lease_start }}" autocomplete="off" placeholder="Anticipate Start">
                            </div>
                            @endif
                            <div class="col-md-6">
                                <label>Anticipated End</label>
                                <input type="text" class="form-control" name="lease_end" value="{{ $student->lease_end }}" autocomplete="off" placeholder="Anticipate End">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Profile</button>
                </div>
            </form>
        </div>

    </div>
</div>