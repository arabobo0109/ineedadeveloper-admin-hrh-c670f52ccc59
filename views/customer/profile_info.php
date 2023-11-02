<style>
    .contact .col-sm-2 {
        color: #A9ABB3;
        padding-right: 15px;
    }
    .contact .col-sm-10,.contact .col-sm-5,.contact .col-sm-3 {
        color: #000;
        font-weight: 600;
    }
    .contact .btn{
        margin: 6px;
    }
</style>
<div class="row bg-white">
    <div class="col-md-9">
        <div class="row line-height-30 contact" style="padding: 10px">
            <div class="col-sm-2">  Email    </div>
            <div class="col-sm-10">  {{ $student->email }} </div>
            <div class="col-sm-2">  Address    </div>
            <div class="col-sm-10">  {{ $student->address }} {{ $student->city }} {{ $student->state }}, {{ $student->country }} </div>
            <div class="col-sm-2">  Phone    </div>
            <div class="col-sm-3">  {{ $student->phone_number }} </div>
            <div class="col-sm-2">  Employer    </div>
            <div class="col-sm-5">  {{$student->employer_name}}    </div>
            <div class="col-sm-2">  PinCode </div>
            <div class="col-sm-3">  {{ $student->pin }}    </div>
            <div class="col-sm-2">  Sponsor    </div>
            <div class="col-sm-5">  {{ $student->sponsor }}    </div>
        </div>
    </div>
    <div class="col-md-3 contact">
        <button class="btn btn-primary" data-toggle="modal" data-target="#edit" data-backdrop="static" data-keyboard="false">Edit</button>
        @if($user->role != "user")
            <a href="{{url('PrintController@idPrint').$student->id}}" id="printBtn" class="btn btn-success" >ID Print</a>
            <a href="{{url('PrintController@proofOfAddress').$student->id}}"  class="btn btn-success" >Proof of Address</a>
        @endif
    </div>
</div>

<div class="mg-top-20 admin-student">
    <a class="btn btn-success btn-2" href="{{ url('Student@viewRoommate').$student->id }}">Roommates</a>

    <a class="btn btn-success btn-2" href="{{ url('Student@takePicture').$student->id }}">Take Picture </a>
    @if (env("SITE_Portal"))
    <a class="btn btn-success btn-2" href="{{ url('Student@takePictureID').$student->id }}">VISA/ID </a>
    @endif

    @if($student->sign_status == 'Lease on File')
        <a class="btn btn-success btn-2"  href="{{ url('Customer@uploadLease').$student->id }}">Upload Lease</a>
        <a class="btn btn-success btn-2" target="_blank" href="{{ url('/uploads/lease/').$student->lease_upload }}">View Lease Doc</a>
    @else
        <a class="btn btn-success btn-2" href="{{ url('Document@open').$document_key }}">View Lease Doc</a>
    @endif

    @if($user->role!='user')
        <button class="btn btn-success btn-2" data-toggle="modal" data-target="#send-email" data-backdrop="static" data-keyboard="false">Send Email</button>

        @if($student->status != 'Terminated')
            <a class="send-to-server-click btn btn-success btn-2"  data="email:{{ $student->email }}|user_id:{{ $student->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Customer@resendDocusign");?>" warning-title="Are you sure?" warning-message="The docusign email will be sent to this student's email." warning-button="Continue" loader="true" href="">Resend Docusign</a>
            <a class="send-to-server-click btn btn-success btn-2"   data="email:{{ $student->email }}|csrf-token:{{ csrf_token() }}" url="<?=url("Customer@sendCheckin");?>" warning-title="Are you sure?" warning-message="The check-in link will be sent to this student's email." warning-button="Continue" loader="true" href="">Send Checkin</a>

            @if ($student->status!='Created')
                <a class="fetch-display-click btn btn-success btn-2"  data="customerid:{{ $student->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Customer@add_fine");?>" holder=".holder-add-fine" modal="#add_fine" href=""> Fine / Room Inspection</a>
            @endif
        @endif
        @if ($student->status!='Created')
            <a class="btn btn-success btn-2"  href="{{url("Checkout@remaining_balance").$student->id}}" >Final Checkout </a>
        @endif
        @if (env("SITE_Portal"))
            @if ($student->bed_id==0)
                <button class="btn btn-success btn-2" onclick="swal('No Room!', 'This student was not any room yet.', 'error')">Door Access</button>
            @else
                <a class="btn btn-success btn-2" href="{{ url('Zk@index').$student->id }}">Door Access</a>
            @endif
        @endif
        @if ($student->room_id!=0)
            <a class="btn btn-success btn-2" href="{{ url('Maintenance@tab').$student->id }}">Maintenance</a>
        @endif
    @endif
</div>

<?php include "profile_invoice.php" ?>
<?php include "profile_include.php" ?>