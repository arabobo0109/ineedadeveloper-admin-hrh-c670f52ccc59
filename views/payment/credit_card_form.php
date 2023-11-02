<!--payments/credit_card_form.php-->
<div class="payment credit-card-form">
    <div class="row">
        @if (isset($parentPage) && $parentPage=='group_payment')
        <form class="simcy-form" action="<?= url("CC@submitCard"); ?>" method="post" style="padding: 15px">
        @else
        <form  action="<?= url("CC@submitCard"); ?>" method="post" style="padding: 15px">
        @endif
            <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">
            <input type="hidden" name="parentPage" value="{{ $parentPage }}">
            <input type="hidden" name="selected_user_ids" value="{{ $selected_user_ids }}">

            <input type="hidden" name="payment_user_id" value="{{ $_POST['payment_user_id'] }}">
            <input type="hidden" name="payment_option" value="{{ $_POST['payment_option'] }}">
            <input type="hidden" name="price_total" value="{{$_POST['price_total']}}">
            <input type="hidden" name="payment_type" value="{{$_POST['payment_type']}}">

            <input type="hidden" name="holding_amount" value="{{$_POST['holding_amount']}}">

            @if (isset($_POST['owed_check']))
            @foreach ($_POST['owed_check'] as $index =>$owed_id)
                <input type="hidden" name="owed_check[]" value="{{$owed_id}}">
                <input type="hidden" name="owed_amount[]" value="{{$_POST['owed_amount'][$index]}}">
            @endforeach
            @endif

        @if( $page == "Subscribe")
        <div class="form-group">
            <div class="payment-detail">
                <div class="row total">
                    <div class="col-xs-6">
                        Payment Amount<br>(Weekly Room Fee)
                    </div>
                    <div class="col-xs-6">
                        ${{$weekly_amount}}
                        <input type="hidden" name="weekly_amount" value="{{$weekly_amount}}">
                        <input type="hidden" name="plan_id" value="{{$plan_id}}">
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="billing-information">
            @if ($page == "Legacy_pos")
                <div class="form-group" style="font-size: 18px;font-weight: bold;">
                    Please make payment <label style="color: red">${{$_POST['price_total']}}</label> in Terminal App
                </div>
                <img src="<?= url(""); ?>assets/images/terminal_app.png" alt="terminal" class="img-responsive" style="margin: auto;margin-bottom: 30px">
            @else
            <div class="form-group" style="font-size: 18px;font-weight: bold;">Billing Information</div>
            <div class="form-group" style="display: inline-block;">
                <div class="col-md-6">
                    <label>First Name</label>
                    <input type="text" class="form-control" name="fname" value="{{ $student->fname }}" placeholder="First Name" data-parsley-required="true" >
                </div>

                <div class="col-md-6">
                    <label>Last Name</label>
                    <input type="text" class="form-control" name="lname" value="{{ $student->lname }}" placeholder="Last Name" data-parsley-required="true">
                </div>
            </div>
            <div class="form-group" style="display: inline-block;width: 100%;" >
                <div class="col-md-12">
                    <label>Address</label>
                    <input type="text" class="form-control" name="address" placeholder="Address" value="{{ $student->address }}">
                </div>
            </div>
            <div class="form-group" style="display: inline-block;">
                <div class="col-md-6">
                    <label>City</label>
                    <input type="text" class="form-control" name="city" value="{{ $student->city }}" placeholder="City" data-parsley-required="true" >
                </div>

                <div class="col-md-6">
                    <label>State</label>
                    <input type="text" class="form-control" name="state" value="{{ $student->state }}" placeholder="State" data-parsley-required="true">
                </div>
            </div>
            <div class="form-group" style="display: inline-block;">
                <div class="col-md-6">
                    <label>Zip</label>
                    <input type="text" class="form-control" name="zip" value="{{ $student->zip }}" placeholder="Zip" data-parsley-required="true" >
                </div>

                <div class="col-md-6">
                    <label>Country</label>
                    <input type="text" class="form-control" name="country" value="{{ $student->country }}" placeholder="Country" data-parsley-required="true">
                </div>
            </div>
            <div class="form-group" style="display: inline-block;">
                <div class="col-md-6">
                    <label>Phone</label>
                    <input type="text" class="form-control" name="phone" value="{{ $student->phone }}" placeholder="Phone" data-parsley-required="true" >
                </div>

                <div class="col-md-6">
                    <label>Email</label>
                    <input type="text" class="form-control" name="email" value="{{ $student->email }}" placeholder="Email" data-parsley-required="true">
                </div>
            </div>
            @endif
        </div>

        <div class="card-information">
        @if( $page == "Terminal")
            <img src="<?= url(""); ?>assets/images/terminal_device.png" alt="terminal" class="img-responsive" style="margin: auto;margin-bottom: 30px">
            <div class="form-group" style="font-size: 18px;font-weight: bold;">
                After click the "Pay" Button, Please tap or swipe card into the Terminal Device
            </div>
        @elseif( $page == "Legacy_pos")
            <img src="<?= url(""); ?>assets/images/terminal_device.png" alt="terminal" class="img-responsive" style="margin: auto;margin-bottom: 30px">
            <div class="form-group" style="font-size: 18px;font-weight: bold;">
                <label style="color: red">*</label>
                Only after successful payment in the terminal app, click "Pay" button
            </div>
        @else
            <img src="<?= url(""); ?>assets/images/2checkout-cards.png" alt="Credit Card" class="img-responsive" style="margin: auto;margin-bottom: 30px">
            <div class="form-group" style="display: inline-block;width: 100%;" >
                <div class="col-md-12">
                    <label for="cardNumber">Card Number</label>
                    <input type="text" class="form-control" id="cardNumber" name="ccnumber" placeholder="" required="">
                </div>
            </div>
            <div class="form-group" style="display: inline-block;width: 100%;">
                <div class="col-md-12">
                    <label data-toggle="tooltip" title=""
                           data-original-title="3 digits code on back side of the card">CVV </label>
                    <input type="number" class="form-control" id="cvv" required="" name="cvv">
                </div>
            </div>
            <div class="form-group" style="display: inline-block;width: 100%;">
                <div class="col-md-12">
                    <label>Expiration Date</label>
                    <select name="ccexpmm">
                        <option value="01">January</option>
                        <option value="02">February </option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>

                    <select name="ccexpyy">
                        <option value="21"> 2021</option>
                        <option value="22"> 2022</option>
                        <option value="23"> 2023</option>
                        <option value="24"> 2024</option>
                        <option value="25"> 2025</option>
                        <option value="26"> 2026</option>
                        <option value="27"> 2027</option>
                        <option value="28"> 2028</option>
                        <option value="29"> 2029</option>
                        <option value="30"> 2030</option>
                    </select>
                </div>
            </div>
        @endif
            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-primary btn-lg pull-right" >
                    Pay
                </button>
            </div>
        </div>
    </form>
    </div>
</div>
