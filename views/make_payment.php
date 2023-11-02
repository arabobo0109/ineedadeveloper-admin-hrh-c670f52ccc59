<?php include "includes/head.php" ?>
<body>
{{ view("includes/header", $data); }}
{{ view("includes/sidebar", $data); }}
<div class="content">
    {{ view("customer/profile_header", $data); }}
    <h3>Payment</h3>
    <div class="row">
        <div class="take-payment-admin light-card">
            <form id="paymentForm" name="paymentForm" onsubmit="return validatePaymentForm()" method="post" action="<?= url("Payment@submitPaymentMode"); ?>" >

                @if ( $user->role =='user' )
                <input type="hidden" name="payment_option" value="by_user">
                @else
                <input type="hidden" name="payment_option" value="by_admin">
                @endif

                <?php include "payment/fee_check.php" ?>
                <h4>Please select a payment method</h4>

                @if ( $student->holding_balance>0 )
                    <button type="submit" name="payment_type" class="btn btn-primary" value="Holding" style="background-color: #006bca">
                        Pre-payment
                    </button>
                @endif
                <button type="submit" name="payment_type" class="btn btn-primary" value="Credit Card">
                    <i class="ion-card"></i>
                    Credit Card
                </button>
                @if ( $user->role =='user' )
                    <a href="<?=url("Payment@pay_at_checkin");?>" class="btn btn-primary">Pay at checkin</a>
                @else
                    @if (strlen($user->season)>20)
                        <button type="submit" name="payment_type" class="btn btn-primary" value="Terminal">
                            <i class="ion-calculator"></i>
                            Cloud Terminal
                        </button>
                    @endif
<!--                    <button type="submit" name="payment_type" class="btn btn-primary" value="Legacy_pos">-->
<!--                        Terminal App-->
<!--                    </button>-->
                    <button type="submit" name="payment_type" class="btn btn-primary"
                            value="Cash">
                        <i class="ion-cash"></i>
                        Cash
                    </button>
                    <button type="submit" name="payment_type" class="btn btn-primary"
                            value="Check">
                        <i class="ion-ios-checkmark"></i>
                        Check
                    </button>
                    <button type="submit" name="payment_type" class="btn btn-primary"
                            value="Credit" style="background-color: #01d6f9">
                        Add Credit
                    </button>
<!--                    @if ( $student->security_deposit>0 )-->
<!--                    <button type="submit" name="payment_type" class="btn btn-primary"-->
<!--                            value="Deposit" style="background-color: #01d6f9">-->
<!--                        Security Deposit-->
<!--                    </button>-->
<!--                    @endif-->
                @endif
                    <!--        @if ( $student->is_subscribe )-->
                    <!--        <button type="submit" name="payment_type" class="btn btn-primary"-->
                    <!--                value="Unsubscribe">-->
                    <!--            Cancel Autopay-->
                    <!--        </button>-->
                    <!--        @else-->
                    <!--        <button type="submit" name="payment_type" class="btn btn-primary"-->
                    <!--                value="Subscribe">-->
                    <!--            Autopay-->
                    <!--        </button>-->
                    <!--        @endif-->
            </form>
        </div>
    </div>
</div>

<?php include "includes/footer_new.php" ?>
