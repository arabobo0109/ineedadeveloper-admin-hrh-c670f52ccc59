<div class="light-card" style="margin-top: 10px">
    <div class="timeline">
        <div class="row text-align-center margin-0">
            <button class="btn btn-success" data-toggle="modal" data-target="#note_dialog" >Add Note</button>
        </div>
        <div class="circle"></div>
        <ul>
            @foreach ( $user->notes as $history )
            <li class="{{ $history->type }}">
                <em class="text-xs">{{ setToUserTimezone($history->created_at) }}
                    &nbsp;&nbsp;<span class="text-primary" style="font-size: 12px">{{ $history->author_name }}</span>
                    @if (date('Ymd')==date('Ymd',strtotime($history->created_at)) && $history->type=='internal' && $history->author_id==$user->id)
                    &nbsp;&nbsp;<button class="btn btn-success edit-note-btn" style=" padding: 1px 13px !important;    font-size: 12px;" data-toggle="modal" data-target="#edit_note_dialog" data-note="{{ $history->content }}" data-id="{{ $history->id }}">Edit</button>
                    @endif
                </em>
                {{ $history->content }}
            </li>
            @endforeach
        </ul>
        <div class="circle"></div>
    </div>
</div>


<!-- Update Note dialog -->
<div class="modal fade" id="edit_note_dialog" role="dialog">
    <div class="close-modal" data-dismiss="modal">&times;</div>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content" >
            <div class="modal-header">
                <h4 class="modal-title">Edit Notes</h4>
            </div>
            <form class="simcy-form" action="<?=url("Student@saveNote");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                <input type="hidden" name="note_id" value="0" />

                <div class="modal-body">
                    @if ($user->role!='user')
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Edit Internal note (Only Admin Staff can see)</label>
                                <textarea class="form-control" name="edit_note_content" rows="3" ></textarea>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Note dialog -->
<div class="modal fade" id="note_dialog" role="dialog">
    <div class="close-modal" data-dismiss="modal">&times;</div>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content" >
            <div class="modal-header">
                <h4 class="modal-title">Add Notes</h4>
            </div>
            <form class="simcy-form" action="<?=url("Student@saveNote");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="{{ $student->id }}">
                <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />

                <div class="modal-body">
                    @if ($user->role!='user')
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Internal note (Only Admin Staff can see)</label>
                                <label style="float: right"><input type="checkbox" class="switch" name="email_support" value="checked" >Email Support</label>
                                <textarea class="form-control" name="internal_note" rows="3" ></textarea>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>External note</label>
                                <textarea class="form-control" name="extra_note" rows="3" ></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .modal-body .form-group .row{
        width: initial;
    }
</style>
