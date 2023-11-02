<div class="bg-white padding-10">
    <div class="row text-align-center margin-0">Status : <label class="color-red">{{$student->status}}</label></div>
    @if($user->role != "user" )
    <div class="row text-align-center margin-0">
        @if($student->status == 'Created')
            <a class="send-to-server-click btn btn-primary"   data="user_id:{{ $student->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Customer@set_arrived");?>" warning-title="Are you sure?" warning-message="Was this student arrived? Lease will be started now." warning-button="Yes" loader="true" href="">Set Arrived</a>
        @elseif($student->flag!='Violation')
            <a class="send-to-server-click btn btn-danger"   data="user_id:{{ $student->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Customer@violation");?>" warning-title="Are you sure?" warning-message="will put violation flag." warning-button="Yes" loader="true" href="" style="background: #444444;  border: #444444;">Set Violation</a>
        @else
            Flag : <label class="color-red">{{$student->flag}}</label>
        @endif
    </div>
    @endif

</div>

<div class="bg-white padding-10" style="margin-top: 10px">
    @if ($student->status!='Created')
    <div class="row text-align-center">
        Arrived Date :&nbsp;
        <label>{{ $student->lease_start }}</label>
    </div>
    <div class="row text-align-center">
        Extended Date &nbsp;&nbsp;:&nbsp;
        <label>{{ $student->real_end }}</label>
    </div>
    @else
    <div class="row text-align-center">
        Anticipated Start :&nbsp;
        <label>{{ $student->lease_start }}</label>
    </div>
    @endif
    <div class="row text-align-center">
        Anticipated End &nbsp;&nbsp;:&nbsp;
        <label>{{ $student->lease_end }}</label>
    </div>
    @if ($student->status=='Terminated')
        <div class="row text-align-center">
            Terminated At :&nbsp;
            <label>{{ $student->checked_out_at }}</label>
        </div>
        @if($user->role != "user" )
            <div class="row text-align-center">
                @if($student->flag=='Violation')
                    <a class="send-to-server-click btn btn-primary"   data="user_id:{{ $student->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Customer@comeback");?>" warning-title="Are you sure?" warning-message="This student was flagged as Violation. A new account will be created and Violation Flag will be removed." warning-button="Yes" loader="true" href="">Come Back Again
                    </a>
                @else
                    <a class="send-to-server-click btn btn-primary"   data="user_id:{{ $student->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Customer@comeback");?>" warning-title="Are you sure?" warning-message="Did this student come back again? A new account will be created." warning-button="Yes" loader="true" href="">Come Back Again
                </a>
                @endif
            </div>
        @endif
    @elseif($student->status=='Paused' )
        <div class="row text-align-center">
            Paused At :&nbsp;
            <label>{{ $student->checked_out_at }}</label>
        </div>
        @if($user->role != "user" )
        <div class="row text-align-center">
            <a class="send-to-server-click btn btn-primary"   data="user_id:{{ $student->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Customer@comeback");?>" warning-title="Are you sure?" warning-message="Did this student come back again?" warning-button="Yes" loader="true" href="">Come Back Again
            </a>
        </div>
        @endif
    @elseif($user->role != "user" )
        <div class="row text-align-center">
            @if($student->status == 'Created' || $student->status == 'Arrived' )
                <button class="btn btn-primary" data-toggle="modal" data-target="#change-lease-start" data-backdrop="static" data-keyboard="false">Change Arrived Date</button>
            @endif
            <button class="fetch-display-click btn btn-primary" data="customerid:{{ $student->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Customer@cancel_lease");?>" holder=".holder-cancel-lease" modal="#cancel_lease_modal" href="">Cancel Lease</button>
        </div>
    @endif
</div>

<div class="bg-white padding-10 mg-top-20">
    <div class="row text-align-center margin-0">
        Weekly Rate :&nbsp;
        <label class="color-red">${{ $student->weekly_rate }}</label>
    </div>
    @if($user->role != "user" )
    <div class="row text-align-center margin-0">
        <button class="btn btn-primary" data-toggle="modal" data-target="#specific-rate" data-backdrop="static" data-keyboard="false">Change Specific Rate</button>
    </div>
    @endif
</div>

<?php include "modal_cancel_lease.php" ?>