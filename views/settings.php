<?php include "includes/head.php" ?>

<body class="settings_template">

    <!-- header start -->
    {{ view("includes/header", $data); }}

    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}

    <div class="content">
        <div class="page-title">
            <h3>Settings</h3>
        </div>
        <div class="light-card settings-card">
            <div class="settings-menu">
                <ul>
                    <li class="active"><a data-toggle="tab" class="tab" href="#profile">Profile</a></li>
                    @if($user->role == "user")
                    <li><a data-toggle="tab" class="tab" href="#remaining_balance">Remaining Balance</a></li>
                    @endif
                    @if ( $user->role != "user" )
                    <li><a data-toggle="tab" class="tab" href="#company">Fees</a></li>
                    <li><a data-toggle="tab" class="tab" href="#monday_email_tab">Monday Report Emails</a></li>
                    <li><a data-toggle="tab" class="tab" href="#reminders">Email Reminders</a></li>
<!--                    <li><a data-toggle="tab" class="tab" href="#payment_reminders">Payment Reminder</a></li>-->
                    @endif
                    <li><a data-toggle="tab" class="tab" href="#password">Password</a></li>
                </ul>
            </div>
            <div class="settings-forms">
                <div class="col-md-8 tab-content">
                    <!-- Profile start -->
                    <div id="profile" class="tab-pane fade in active">
                        <h4>Profile</h4>
                        <form class="simcy-form" id="setting-profile-form" action="<?=url("Settings@updateprofile");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>First name</label>
                                        <input type="text" class="form-control" name="fname" value="{{ $user->fname }}" placeholder="First name" required>
                                        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Last name</label>
                                        <input type="text" class="form-control" name="lname" value="{{ $user->lname }}" placeholder="Last name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Email address</label>
                                        <input type="email" class="form-control" name="email" value="{{ $user->email }}" placeholder="Email address" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Phone number</label>
                                        <input type="text" class="form-control" name="phone" value="{{ $user->phone }}" placeholder="Phone number">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Address</label>
                                        <input type="text" class="form-control" name="address" value="{{ $user->address }}" placeholder="Address">
                                    </div>
                                </div>
                            </div>
	                        @if ( $user->role == "user")
	                        <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Pin Code (If you change Pin Code please click "Generate")</label>
                                        <input type="text" name="pin" class="form-control pin-control" value="{{ $user->pin }}" >
                                        <a class="btn btn-primary pin-generate-bt">Generate</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Room Number</label>
                                        <input type="text" class="form-control" value="{{ $room->name }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Bed Name</label>
                                        <input type="text" class="form-control" value="{{ $bed->name }}" disabled>
                                    </div>
                                </div>
                            </div>
	                        @endif
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Timezone is {{date('T')}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button class="btn btn-primary" type="submit" >Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                    <!-- profile end -->
                    @if ( $user->role != "user" )
                    <?php include "settings/monday_email.php" ?>
                    <?php include "settings/checkin_reminder.php" ?>
                    <?php include "settings/payment_reminder.php" ?>
                    <div id="company" class="tab-pane fade">
                        <h4>Fees</h4>
                        <form class="simcy-form" id="setting-company-form" action="<?=url("Settings@updatecompany");?>" data-parsley-validate="" loader="true" method="POST">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Room Weekly Fee($)</label>
                                        <input type="number" class="form-control" name="weekly" placeholder="weekly fee" value="{{ $company->weekly}}" required id="weekly_input">
                                        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                                    </div>
                                    <div class="col-md-6">
                                        <label>Room Daily Fee($)</label>
                                        <input type="text" class="form-control"  placeholder="daily fee" value="{{ $company->daily }}" disabled id="daily_input">
                                        <input type="hidden" class="form-control" name="daily" placeholder="daily fee" value="{{ $company->daily }}" id="daily_input_hidden">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Security Deposit($)</label>
                                        <input type="text" class="form-control" name="security" placeholder="Security Deposit" value="{{ $company->security }}" data-parsley-required="true">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Administration Fee($)</label>
                                        <input type="text" class="form-control" name="administration" placeholder="administration fee" value="{{ $company->administration }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Laundry Fee($)</label>
                                        <input type="text" class="form-control" name="laundry" placeholder="Laundry Fee" value="{{ $company->laundry}}" data-parsley-required="true">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button class="btn btn-primary" type="submit">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                    <!-- Company end -->
                    @endif
                    <!-- Remaining Balance start -->
                    <div id="remaining_balance" class="tab-pane fade">
                        <h4>Remaining Balance</h4>
                        <p>This is your remaining balance return from admin.</p><br>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Your current balance is <span class="color-red">${{$user->balance}}</span> </label>
                            </div>
                        </div>
                    </div>
                    <!-- Remaining Balance end -->

                    <!-- password start -->
                    <div id="password" class="tab-pane fade">
                        <h4>Password</h4>
                        <form class="simcy-form"action="<?=url("Settings@updatepassword");?>" data-parsley-validate="" loader="true" method="POST">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Current password</label>
                                        <input type="password" class="form-control" name="current" required placeholder="Current password">
                                        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>New password</label>
                                        <input type="password" class="form-control" name="password" data-parsley-required="true" data-parsley-minlength="6" id="newPassword" placeholder="New password">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Confirm password</label>
                                        <input type="password" class="form-control" data-parsley-required="true" data-parsley-equalto="#newPassword" placeholder="Confirm password">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button class="btn btn-primary" type="submit">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                    <!-- password end -->
                </div>
            </div>
        </div>
    </div>


<!-- scripts -->
<script>
    var fullName = "{{ $user->fname }} {{ $user->lname }}",
        saveSignatureUrl = "<?=url("Signature@save");?>",
        auth = true;
    
</script>

<?php include "includes/footer_new.php" ?>
<script>
	$(document).ready(function() {
        $( ".pin-generate-bt" ).click(function() {
        	var new_pin = Math.floor(Math.random() * (999999 - 100000) + 100000);
        	$( ".pin-control" ).val(new_pin);
        });

        $( "#weekly_input" ).keyup(function() {
            console.log("aa");
            var daily_fee = Math.round($(this).val() / 7 * 100) / 100;
            $( "#daily_input" ).val(daily_fee);
            $( "#daily_input_hidden" ).val(daily_fee);
        });
    });
</script>




