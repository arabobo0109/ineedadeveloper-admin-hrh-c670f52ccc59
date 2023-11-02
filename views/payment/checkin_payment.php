{{ view("includes/head", $data); }}

<body>
<style>
.content{
    width: 1000px;
    margin: 0 auto;
}

.panel-heading{
	padding: 10px 15px;
    font-size: 16px;
}
</style>
{{ view("includes/header", $data); }}
<div class="content">
    {{ view("customer/profile_header", $data); }}
    <h3>Check in</h3>
    <div class="row">
        <form method="post" onsubmit="return validatePaymentForm()" action="<?= url("Payment@submitPaymentMode"); ?>">
            <input type="hidden" name="payment_option" value="checkin">
            <?php include "fee_check.php" ?>
            <h4>Please choose payment method</h4>
            <button type="submit" name="payment_type" class="btn btn-primary"
                    value="Credit Card">
                Credit Card
            </button>
<!--                <button type="submit" name="payment_type" class="btn btn-primary"-->
<!--                        value="Paypal">-->
<!--                    Paypal-->
<!--                </button>-->
            <button type="submit" name="payment_type" class="btn btn-primary"
                    value="cashier_desk">
                Pay at Window
            </button>
        </form>
    </div>
</div>
{{ view("includes/footer_camera"); }}
</body>

</html>