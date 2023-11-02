{{ view("includes/head", $data); }}
<body>

{{ view("includes/header", $data); }}
{{ view("includes/sidebar", $data); }}

<div class="content">
    <?php include "profile_header.php" ?>
    <div class="row">
        <div class="light-checkin table-responsive">
            <div class="">
                <form class="simcy-form page-actions" method="POST" loader="true" action="<?=url("Checkout@refundRemainingBalance");?>"
                      enctype="multipart/form-data">
                    <input type="hidden" name="csrf-token" value="{{ csrf_token(); }}"/>
                    <input type="hidden" name="user_id" value="{{ $student->id }}"/>
                    <div class="">
<!--                        <label>Total Amount of Fine : ${{ $totalFineFee }}</label>-->
                        <p>Total owed balance (Including room owed balance and fine fees from room inspect): <span class="color-red"> ${{ $student->balance}}</span></p>
<!--                        <p>Remain Balance(Security fee -Total owed balance): <span class="color-red"> ${{ $rem_bal}}</span></p>-->
                            <hr>
                            <label>Security Deposit History</label>
                            <div class="light-card table-responsive">
                                <table class="table display companies-list" id="data-table">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                        <th>Transaction ID</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($security_deposit_history))
                                    @foreach($security_deposit_history as $each_history)
                                    <tr>
                                        <td>{{ $each_history->created_at }}</td>
                                        <td>{{ $each_history->security_paid }}</td>
                                        <td>{{ $each_history->payment_mode }}</td>
                                        <td>
                                        @if(($each_history->payment_mode == "Paypal") || ($each_history->payment_mode == "Credit Card") )
                                            {{ $each_history->transaction_id }}
                                        @endif
                                        </td>
                                        <td>{{ $each_history->status }}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <br>
                        <div class="form-group">
                                    <label>Refund Amount : </label>
                                    <input type="number" name="return_balance" value="" data-parsley-required="true" >

                                    Payment Method :
                                    <select name="payment_mode" >
                                        <option value="Cash">Cash</option>
                                        <option value="Check">Check</option>
                                        <option value="Credit Card">Credit Card</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Refund</button>
                        </div>
                        <label>Don’t Forget to take the money from the security deposit boxes!</label>
                        <br><br>
                        <label>Security Deposit Refund History</label>
                        <div class="light-card table-responsive">
                            <table class="table display companies-list" id="data-table">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($refund_deposit_history))
                                        @foreach($refund_deposit_history as $each_history)
                                        <tr>
                                            <td>{{ $each_history->created_at }}</td>
                                            <td>{{ $each_history->security_paid }}</td>
                                            <td>{{ $each_history->payment_mode }}</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <a class="send-to-server-click btn btn-success"  data="email:{{ $student->email }}|user_id:{{ $student->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Checkout@finalCheckout");?>" warning-title="Don’t Forget to take the money from the security deposit boxes!" warning-message="This student will be checked out. And the bed status will be unavailable." warning-button="Continue" loader="true" href="">Final Checkout</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- footer -->
{{ view("includes/footer"); }}

<style>
    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 25px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 30px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>
</body>
</html>