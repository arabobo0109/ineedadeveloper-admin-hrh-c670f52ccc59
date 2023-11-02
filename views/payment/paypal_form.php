<form action="/submitPaypal/" method="post" name="paypal_submit_form">
    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">
    <input type="hidden" name="price_total" value="{{$_POST['price_total']}}">
    <input type="hidden" name="payment_user_id" value="{{ $student->id }}">
    <input type="hidden" name="parentPage" value="{{ $parentPage }}">
    <input type="hidden" name="payment_option" value="{{ $_POST['payment_option'] }}">
    <input type="hidden" name="response" value="">

    <div class="form-group">
        <div class="payment-detail">
            <div class="row total">
                <div class="col-xs-3">
                    Payment Amount
                </div>
                <div class="col-xs-4">
                    ${{ $_POST['price_total'] }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div id="paypal-button-container"></div>
    </div>
</form>
<?php include "paypal_script.php" ?>
