<!-- Send Email -->
<div class="modal fade" id="send-email" role="dialog">
    <div class="close-modal" data-dismiss="modal">&times;</div>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content" >
            <div class="modal-header">
                <h4 class="modal-title">Send Email to Student</h4>
            </div>
            <form class="simcy-form" id="create-template-form" action="<?=url("EmailTemplate@sendEmail");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />

                <div class="modal-body">
                    @if (isset($student))
                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                    @else
                    <p>Please choose the status of target students</p>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Student Status</label> <label class="color-red">*</label>
                                <select class="form-control" name="status">
                                    <option value='Created'>Created</option>
                                    <option value='Arrived'>Arrived</option>
                                    <option value='Extended'>Extended</option>
                                    <option value='Terminated'>Terminated</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    @endif
                    <p>Please choose Email Template or Custom Email</p>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="checkbox" class="switch" value="1" id="email_switch"/>
                                <label class="form-check-label">Custom Email</label>
                                <input type="hidden" name="email_switch" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="form-group email-template-area">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Email Template</label>
                                <select class="form-control" name="email_template_id">
                                    @foreach($email_templates as $email_template)
                                    <option value="{{$email_template->id}}">{{$email_template->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="custom-email-area">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Title</label> <label class="color-red">*</label>
                                    <input type="text" class="form-control" name="mail_title" placeholder="Email title"  >
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Content</label> <label class="color-red">*</label>
                                    <input type="hidden" name="content" id="content_hidden">
                                    <textarea class="form-control" id="content" placeholder="Email Content"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary submit-bt">Send</button>
                </div>
            </form>
        </div>

    </div>
</div>

<script src="<?=url("");?>assets/js/ckeditor/ckeditor.js"></script>
<script src="<?=url("");?>assets/js/ckeditor/adapters/jquery.js"></script>

<script>
    $(document).ready(function() {
        $("#content").ckeditor();

        $(".custom-email-area").hide();
        $("[id='email_switch']").change(function(){
            if($(this).is(':checked')){
                $("[name='email_switch']").val("1");
                $(".custom-email-area").show();
                $(".email-template-area").hide();
            }
            else{
                $("[name='email_switch']").val("0");
                $(".custom-email-area").hide();
                $(".email-template-area").show();
            }
        });

        $(".submit-bt").click(function()
        {
            var ckeditor_content = encodeURI(CKEDITOR.instances.content.getData());
            $("#content_hidden").val(ckeditor_content);
            $("#create-template-form").submit();
        });
    });
</script>