{{ view("includes/head", $data); }}
<body>
{{ view("includes/header", $data); }}
{{ view("includes/sidebar", $data); }}

<div class="content">
    <?php include "profile_header.php" ?>
    <div class="row">
        <div class="col-md-8 ">
            <?php include "profile_info.php" ?>
        </div>
        <div class="col-md-4">
            <?php include "profile_right.php" ?>
            <div class="bg-white padding-10" style="margin-top: 10px">

                <div class="row text-align-center margin-0">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#view_balance_history" data-backdrop="static" data-keyboard="false">Balance History</button>
                </div>

                    <div class="row text-align-center margin-0" style="margin-top:10px">
                        @if (env('SITE_Portal') && $student->drawer_number==0)
                            <label class="color-red">* Please open a drawer</label>
                        @else
                            @if($student->balance <= 0)
                            <a class="send-to-server-click btn btn-primary" data="customerid:{{ $student->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Payment@take_payment");?>" warning-title="Are you sure?" warning-message="This student has already paid full payment!" warning-button="Make Payment" loader="true" type="form" href="">Make Payment</a>
                            @else
                                <a class="a-form-post btn btn-primary" data="customerid:{{ $student->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Payment@take_payment");?>" href="">Make Payment</a>
                            @endif
                        @endif
                    </div>

                    @if (count($invoice) == 0 && $student->employer > 0)
<!--                        <div class="row text-align-center margin-0" style="margin-top:10px">-->
<!--                            <a class="send-to-server-click btn btn-primary"   data="user_id:{{ $student->id }}|csrf-token:{{ csrf_token() }}" url="--><?//=url("Customer@employer_paid");?><!--" warning-title="Are you sure?" warning-message="Employer ({{$student->employer_name}}) will make all payment." warning-button="Yes" loader="true" href="" >{{$student->employer_name}} will pay</a>-->
<!--                        </div>-->
                    @endif

                <div class="row text-align-center margin-0" style="margin-top:10px">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#add-amount-balance" data-backdrop="static" data-keyboard="false">Add Amount to Balance
<!--                        <span class="badge" style="top: -14px; right: -28px;color: #ffffff; background-color: #ff0000;">new</span>-->
                    </button>
                </div>

            </div>
            <?php include "profile_notes.php" ?>
            
        </div>
        <?php include "action_log_content.php" ?>
    </div>

    <!-- Edit Specific Weekly Rate-->
    <div class="modal fade" id="specific-rate" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content" >
                <div class="modal-header">
                    <h4 class="modal-title">Edit Specific Weekly Rate</h4>
                </div>
                <form class="simcy-form" id="edit-customer-form" action="<?=url("Customer@updateSpecificWeeklyRate");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="customerid" value="{{ $student->id }}">
                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                    <div class="modal-body" style="height: 150px;">
                        <p>Enter Specific Weekly Rate.</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Weekly Rate</label>
                                    <input type="number" class="form-control" name="weekly" value="{{ $student->weekly_rate }}" placeholder="Weekly Rate" data-parsley-required="true" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Rate</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Change Lease Start -->
    <div class="modal fade" id="change-lease-start" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content" >
                <div class="modal-header">
                    <h4 class="modal-title">Change Arrived Date</h4>
                </div>
                <form class="simcy-form" id="edit-customer-form" action="<?=url("Customer@updateLeaseStartDate");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="customerid" value="{{ $student->id }}">
                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                    <div class="modal-body" style="height: 150px;">
                        <p>Please choose date for lease start.</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Lease Start</label>
                                    <input type="text" class="form-control" id="lease_start" name="lease_start" value="{{ $student->lease_start }}" placeholder="Lease Start" data-parsley-required="true" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Date</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Add Amount To Student Balance -->
    <div class="modal fade" id="add-amount-balance" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content" >
                <div class="modal-header">
                    <h4 class="modal-title">Add Amount To Student Balance</h4>
                </div>
                <form class="simcy-form" id="edit-customer-form" action="<?=url("Customer@addAmountToBalance");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="payment_user_id" value="{{ $student->id }}">
                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="payment_option" value="by_admin">
                    <div class="modal-body">
                        <label>Select a type</label><label class="color-red">*</label>
                        <div class="form-group">
                            <div class="row">
                            <?php $balanceType=['Room','Security','Administration','Laundry','Fine'] ?>
                            @foreach($balanceType as $status )
                                <div class="col-md-4">
                                    <label class="radio"><input type="radio" name="type" value="{{$status}}"><span class="outer"><span class="inner"></span></span>{{$status}}</label>
                                </div>
                            @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Amount</label><label class="color-red">*</label>
                                    <input type="number" class="form-control" name="price_total" placeholder="Amount for adding Balance" data-parsley-required="true" min="1" max="1000">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Note</label><label class="color-red">*</label>
                                    <input type="text" class="form-control" name="note_to_balance" data-parsley-required="true">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Balance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include "balance_history_modal.php"?>

    <!--Add Fine dialog-->
    <div class="modal fade" id="add_fine" role="dialog" style="background-color: #fff;">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="holder-add-fine simcy-form" action="<?=url("Fine@updateAddFine");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box"><div class="circle-loader"></div></div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- footer -->
{{ view("includes/footer"); }}

<?php include 'sendMail.php'?>

<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src="<?=url("");?>assets/js/jquery-ui.js"></script>


<script>
    $(document).ready(function() {
        $("#lease_start").datepicker({
            todayHighlight: !0,
            format: 'yyyy-mm-dd'
        });
        $('#balance-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    // autoPrint: false,
                    title:'{{$student->fname.' '.$student->lname.' ('.$student->unit.')' }}',
                    messageTop: 'Total Balance Owed:${{$student->balance}}',
                    messageBottom:'<span style="right: 23px;position: fixed;padding: 10px;color: dodgerblue;">{{env('APP_URL')}}</span>',
                    customize: function ( win ) {
                        $(win.document.body)
                            // .css( 'font-size', '10pt' )
                            .prepend(
                                @if(!empty($student->avatar) && file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/avatar/".$student->avatar))
                        '<img src="{{ env('APP_URL')."/uploads/avatar/".$student->avatar }}"'
                    @else
                        '<img src="{{ env('APP_URL')}}/webcam/assets/img/avatar.jpg"'
                    @endif
                        +'style="border: 2px solid #7AC143;right: 10px;position: absolute;" width="120"/>');
                        $(win.document.body).find( 'table' )
                            .addClass( 'compact' )
                            .css( 'font-size', 'inherit' );
                    }
                },
                'pdfHtml5',
                'excelHtml5'
            ]
        });
    });

</script>

<script>
    // When the Edit button is clicked, populate the internal_note textarea with the note content
    $('.edit-note-btn').on('click', function () {
        var noteContent = $(this).data('note');
        $('textarea[name="edit_note_content"]').val(noteContent);
        $('input[name="note_id"]').val($(this).data('id'));
    });

    // Clear the internal_note textarea when the modal is closed
    $('#edit_note_dialog').on('hidden.bs.modal', function () {
        $('textarea[name="edit_note_content"]').val('');
        $('input[name="note_id"]').val(0);
    });

    $('.refund_btn').on('click', function () {
        let amount = $(this).data('amount');
        let refund_input=$('input[name="refund_balance_amount"]');
        refund_input.val(amount);
        refund_input.attr("max", amount);
        $('#max_refund_amount').text(amount);
        $('input[name="refund_balance_id"]').val($(this).data('id'));
        $('input[name="invoice_id"]').val($(this).data('invoice'));
    });
</script>

<script src="<?= url(""); ?>assets/js/room.js"></script>
<script src="<?= url(""); ?>assets/js/bootstrap-datepicker.min.js"></script>

<script>
    $(document).ready(function() {
        $("[name='birthday']").datepicker({
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });
        $("[name='lease_start']").datepicker({
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });
        $("[name='lease_end']").datepicker({
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });
    });
</script>

</body>

</html>