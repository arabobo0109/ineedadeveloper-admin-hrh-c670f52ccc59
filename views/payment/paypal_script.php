<script
        src="https://www.paypal.com/sdk/js?client-id=<?=env('Paypal_Client_Id');?>"> // Required. Replace SB_CLIENT_ID with your sandbox client ID.
</script>
<script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            // This function sets up the details of the transaction, including the amount and line item details.
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: "{{$_POST['price_total']}}"
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            // This function captures the funds from the transaction.
            return actions.order.capture().then(function(details) {
                // This function shows a transaction success message to your buyer.
                $("[name='response']").val(JSON.stringify(details));
                $("[name='paypal_submit_form']").submit();
            });
        }
    }).render('#paypal-button-container');
    //This function displays Smart Payment Buttons on your web page.
</script>