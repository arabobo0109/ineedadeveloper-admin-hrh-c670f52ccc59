<style>
    .tp-back{
        font-size: 14px !important;
    }
</style>
<div class="page-title" style="font-size: 15px;">
    <div class="row">
        <div class="col-md-7">
            <div class="row" style="margin: 10px">
                <div class="col-md-6" >
                @if($student->identifier == "J")
                    <label style="color:blue;">{{ $student->fname }} {{ $student->lname }}</label>
                @elseif($student->identifier == "H" || $student->intern)
                    <label style="color:#00D000;">{{ $student->fname }} {{ $student->lname }}</label>
                @elseif($student->identifier == "I")
                    <label style="color:#000000;">{{ $student->fname }} {{ $student->lname }}</label>
                @else
                    <label>{{ $student->fname }} {{ $student->lname }}</label>
                @endif

                </div>
                @if( $user->role !='user' )
                    <div class="col-md-4">
                        <label style="color:#82838b;">Bed: </label>
                        <label>{{ $student->unit }}</label>
                    </div>
                @endif
            </div>
            <div class="row">
                @if( $user->page_title ==='Student Profile' )
                <div class="col-md-4">
                    @if( $student->gender=='Male')
                    <img src="<?= url(""); ?>assets/images/profile/male.png" width="25px">
                    @else
                    <img src="<?= url(""); ?>assets/images/profile/female.png" width="25px">
                    @endif
                    <span style="font-size: 14px;color: #A9ABB3">  {{ $student->gender }}</span>
                </div>
                @else
                <div class="col-md-4">
                    @if( $user->role =='user' )
                    <a href="<?=url('Dashboard@get');?>" class="btn btn-primary">Back to Dashboard</a>
                    @else
                    <a href="<?=url("Customer@profile");?>{{$student->id}}" class="btn btn-primary">Back to Student Profile</a>
                    @endif
                </div>
                <div class="col-md-4">
                    <label>{{ $user->page_title }}</label>
                </div>
                @endif
            </div>
        </div>
        <div class="col-md-3" style="margin-top:10px;font-size: 15px " >
            <div class="row text-align-center margin-0">
                Total Balance Owed:&nbsp;
                <label class="color-red">${{ $student->balance }}</label>
            </div>
            <div class="row text-align-center margin-0">
                Prepayments:&nbsp;
                <label class="color-red">${{ $student->holding_balance }}</label>
            </div>
            <div class="row text-align-center margin-0">
                Deposit Held:&nbsp;
                <label class="color-red">${{ $student->security_deposit }}</label>
            </div>
        </div>
        <div class="col-md-2">
            <span class="avatar dash-avatar">
                @if( !empty($student->avatar) )
                <img src="<?= url(""); ?>uploads/avatar/{{ $student->avatar }}" class="user-avatar img-circle">
                @else
                <img src="<?= url(""); ?>assets/images/avatar.png" class="user-avatar">
                @endif
            </span>
        </div>
    </div>
</div>
