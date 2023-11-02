<div id="monday_email_tab" class="tab-pane fade">
    <h4>Email list that receive report on every monday </h4>
    <form class="simcy-form" id="setting-reminder-form" action="<?= url("Settings@updateMondayEmails"); ?>"
          data-parsley-validate="" loader="true" method="POST">
        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}"/>
        <div class="panel-group reminders-holder" id="accordion">
            @foreach ($monday_emails as $index => $reminder)
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if( $index > 0 )
                    <span class="delete-reminder" data-toggle="tooltip" title="Remove email"><i
                                class="ion-ios-trash"></i></span>
                    @endif
                    <h4 class="panel-title">
                        <a data-parent="#accordion" data-toggle="collapse" href="#p_collapse{{ $index + 1 }}">{{ $reminder->email }}</a>
                    </h4>
                </div>
                @if( $index == 0 )
                <div class="panel-collapse collapse in" id="p_collapse{{ $index + 1 }}">
                @else
                    <div class="panel-collapse collapse" id="p_collapse{{ $index + 1 }}">
                        @endif
                        <div class="panel-body">
                            <div class="remider-item">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="hidden" name="count[]" value="1">
                                            <label>Name</label> <input class="form-control" name="name[]"
                                                                                placeholder="Name" required
                                                                                type="text"
                                                                                value="{{ $reminder->name }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Email</label>
                                            <input class="form-control" name="email[]" required type="email"
                                                   value="{{ $reminder->email }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button class="btn btn-default add-monday-email" type="button">Add Email</button>
                    <button class="btn btn-primary" type="submit">Save Changes</button>
                </div>
            </div>
        </div>
    </form>
</div>