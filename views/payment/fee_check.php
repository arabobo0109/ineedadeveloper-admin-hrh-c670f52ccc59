<input type="hidden" name="price_total" value="">
<input type="hidden" name="csrf-token" value="{{ csrf_token() }}">
<input type="hidden" name="payment_user_id" value="{{ $student->id }}">

<div class="light-card table-responsive">
    <table class="table display companies-list" id="data-table">
        <thead>
        <tr>
            <th>Fee Type</th>
            <th>Fee Amount</th>
            <th>Due</th>
            @if($user->role != 'user')
                <th><strong class="color-red">&nbsp;&nbsp;&nbsp;&nbsp;Change</strong></th>
            @endif
        </tr>
        </thead>
        <tbody>

        @foreach ( $owed_list as $owed )
        <tr>
            <td>{{ $owed->note }}</td>
            <td><strong>${{ $owed->amount }}</strong><br></td>
            <td><strong>
                    <input type="checkbox" name="owed_check[]" value="{{ $owed->id }}" checked >
                    ${{ $owed->owed_amount }}
                </strong>
            </td>
            @if($user->role != 'user')
            <td>
                <label class="float-left">$</label>
                <input type="number" class="fee-change" id="{{ $owed->id }}" name="owed_amount[]" min="0" max="{{$owed->owed_amount}}" value="{{$owed->owed_amount}}">
            </td>
            @else
                <input type="hidden" id="{{ $owed->id }}" name="owed_amount[]" value="{{$owed->owed_amount}}">
            @endif
        </tr>
        @endfor

        <tr>
            <td><strong>Pre-Pay</strong></td>
            <td></td>
            @if($user->role != 'user')
            <td>
                <input type="checkbox" name="holding_check" value="holding" >
            </td>
            @endif
            <td><strong>
                    @if($user->role == 'user')
                    <input type="checkbox" name="holding_check" value="holding" >
                    @endif
                    <label>$</label>
                    <input type="number" class="fee-change" id="holding_amount" name="holding_amount" min="0" value="0" disabled></strong></td>
        </tr>

        <tr style="font-size: 16px">
            <td></td>
            @if($user->role != 'user')
            <td></td>
            @endif
            <td><strong>Total Payment Amount:</strong></td>
            <td>
                <strong class="color-red">
                    $<label id="total_payment_label"></label></strong>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<hr>
<script>
    let total_fee = 0;
    $(document).ready(function(){

        // let holding_amount = parseInt($('#holding_amount').val());
        let holding_amount = 0;

        $(".fee-change").on('keyup change', function (){
            let fee_amount_id = $(this).attr('id');
            let fee_amount_val = parseInt($(this).val());
            if(fee_amount_id == "holding_amount")
                holding_amount = fee_amount_val;

            update_total();
        });

        $("[name='owed_check[]']").change(function(){
            if($(this).is(':checked')){
                $('#'+$(this).val()).removeAttr("disabled");
            }
            else{
                $('#'+$(this).val()).attr("disabled", "disabled");

                $('#holding_amount').attr("disabled", "disabled");
                $("[name='holding_check']").prop('checked', false);
                holding_amount=0;
            }
            update_total();
        });

        $("[name='holding_check']").change(function(){
            if($(this).is(':checked')){
                $('#holding_amount').removeAttr("disabled");
                holding_amount=parseInt($('#holding_amount').val());

                $("[name='owed_check[]']").each(function(){
                    this.checked=true;
                    $('#'+$(this).val()).removeAttr("disabled");
                });
            }
            else{
                $('#holding_amount').attr("disabled", "disabled");
                holding_amount=0;
            }
            update_total();
        });


    @if($user->role != 'user')
        $("[name='owed_check[]']").each(function(){
            this.checked=false;
            $('#'+$(this).val()).attr("disabled", "disabled");
        });
    @endif
        update_total();

        function update_total() {
            total_fee = holding_amount;
            console.log(total_fee);
            $("input[name='owed_amount[]']").each(function (){
                if (!this.disabled)
                    total_fee+= parseInt(this.value);
            })
            $("#total_payment_label").html(total_fee);
            $("[name='price_total']").val(total_fee);
        }
    });

    // $(function () {
    //     $( "input" ).change(function() {
    //         var max = parseInt($(this).attr('max'));
    //         var min = parseInt($(this).attr('min'));
    //         if ($(this).val() > max)
    //         {
    //             $(this).val(max);
    //         }
    //         else if ($(this).val() < min)
    //         {
    //             $(this).val(min);
    //         }
    //     });
    // });
    let _formConfirm_submitted = false;
    function validatePaymentForm(){
        if ($(document.activeElement).val()=='Holding'){
            let holdingBalance={{$student->holding_balance}};
            if (total_fee>holdingBalance){
                swal("Insufficient Holding balance", "Holding Balance is $"+holdingBalance+". But you are trying to pay $"+total_fee+".", "error");
                return false;
            }
        }
        else if ($(document.activeElement).val()=='Deposit'){
            let deposit={{$student->security_deposit}};
            if (total_fee>deposit){
                swal("Insufficient security deposit", "Security deposit is $"+deposit+". But you are trying to pay $"+total_fee+".", "error");
                return false;
            }
        }
        if (total_fee<=0){
            swal("Please select what you need to pay.", "Now total payment amount is $"+total_fee, "error");
            return false;
        }
        if (isNaN(total_fee)){
            swal("Total payment amount is blank.", "Now some selected payment amount is blank. \n It should be zero value. \n Please check carefully again.", "error");
            return false;
        }

        if (_formConfirm_submitted == false) {
            _formConfirm_submitted = true;
            return true
        } else {
            alert('your request is being processed!');
            return false;
        }
        // $(".btn-primary").attr("disabled", true);
        // $(":submit").attr("disabled", true);
        // document.querySelectorAll('[type=submit]').forEach(b => b.disabled = true)
        // return true;
    }

    function onHoldingBtn() {  //not used now
        let holdingBalance={{$student->holding_balance}};
        if (total_fee>holdingBalance){
            swal("Insufficient Holding balance", "Holding Balance is $"+holdingBalance+". But you are trying to pay $"+total_fee+".", "error");
        }
        else{
            let form=document.getElementById('paymentForm');
            let input = document.createElement('input');//prepare a new input DOM element
            input.setAttribute('name', 'payment_type');//set the param name
            input.setAttribute('value', 'Holding');//set the value
            input.setAttribute('type', 'hidden')//set the type, like "hidden" or other

            form.appendChild(input);//append the input to the form
            form.submit();
        }
    }
</script>

<style>
    .fee-change{
        width: 100px;
    }
</style>