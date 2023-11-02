<div class="modal-header">
    <h4 class="modal-title">Employer Payment</h4>
</div>
<div class="modal-body" style="height: 100%">
    <div class="row">
        <label> Please select students which make payment by this employer.</label>
    </div>
    <label>Price($) : </label>
    <input type="number" class="form-control" name="price_total" value="100" placeholder="Please input price per student" data-parsley-required="true" >
    <label>The total amount : </label>
    <label id="total_amount" class="color-red" style="font-size: 18px">$0</label>

    <input type="hidden" name="csrf-token" value="{{ csrf_token(); }}"/>
    <input type="hidden" name="payment_user_id" value="{{ $employer_id }}"/>
    <input type="hidden" name="selected_user_ids" value="">
    <div class="light-card table-responsive p-b-3em">
        <table class="table display companies-list" id="select-table">
            <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th class="text-center w-70">Photo</th>
                <th>Name</th>
                <th>Email</th>
                <th>Total owed</th>
                <th>Security</th>
                <th>Admin</th>
                <th>Laundry</th>
                <th>Room</th>
<!--                <th class="text-center">Bed Name</th>-->
                <th class="text-center">Status</th>
            </tr>
            </thead>
            <tbody>
            @if ( count($students) > 0 )
            @foreach ( $students as $customer )
            <tr>
                <td></td>
                <td>{{ $customer->id }}</td>
                <td class="text-center">
                    @if ( !empty($customer->avatar) )
                    <img src="<?=url("")?>uploads/avatar/{{ $customer->avatar }}" class="img-responsive img-circle table-avatar">
                    @else
                    <img src="<?=url("")?>assets/images/avatar.png" class="img-responsive table-avatar">
                    @endif
                </td>
                <td>
                    <a href="<?=url("Customer@profile");?>{{$customer->id}}">
                        <strong>{{ $customer->fname }} {{ $customer->lname }}</strong>
                        @if ( $customer->account_type == "Test" )
                        <span class="label label-info">Test</span>
                        @endif
                    </a>
                </td>
                <td><strong>{{ $customer->email }}</strong></td>
                <td>{{ $customer->balance_owed }}</td>
                <td>${{ $customer->security_owed }}</td>
                <td>${{ $customer->admin_owed }}</td>
                <td>${{ $customer->laundry_owed }}</td>
                <td>${{ $customer->room_owed }}</td>

<!--                <td class="text-center">{{ $customer->unit}}</td>-->
                <td class="text-center">
                                    <span class="label
                                    @if ( $customer->status == 'Created' )
                                        label-warning
                                    @elseif ( $customer->status == 'Arrived' )
                                        label-success
                                    @elseif ( $customer->status == 'Extended' )
                                        label-info
                                    @else
                                        label-danger
                                    @endif
                                    ">
                                        {{ $customer->status }}
                                    </span>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="9" class="text-center">It's empty here</td>
            </tr>
            @endif
            </tbody>
        </table>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Pay with Check</button>
        <input type="button" class="btn btn-primary" id="credit_button" value="Credit Card">
    </div>
</div>

<style>
    table.dataTable>tbody>tr>td.select-checkbox:before, table.dataTable>tbody>tr>td.select-checkbox:after{
        top:initial;
    }

    #select-table > tbody > tr > td:nth-child(6){
        color: red;
        font-weight: bold;
    }

</style>

<script>
    $(document).ready(function() {
        let table=$('#select-table').DataTable({
            'columnDefs': [{
                    orderable: false,
                    'targets': 0,
                    className: 'select-checkbox'}
            ],
            select: {
                style:    'multi+shift',
                // selector: 'td:first-child'
            },
            dom: 'Bfrtip',
            buttons: [
                {
                    text: 'Select all',
                    action: function () {
                        table.rows().select();
                    }
                },
                {
                    text: 'Select none',
                    action: function () {
                        table.rows().deselect();
                    }
                }
            ]
        });

        document.getElementsByName("price_total")[0].addEventListener('change', calc_total);
        function calc_total(){
            let selected_rows = table.rows( { selected: true } ).data();
            let selected_user_ids=selected_rows.pluck(1).toArray();
            let price=document.getElementsByName('price_total')[0].value;
            let total=price*selected_user_ids.length;
            console.log(total,selected_user_ids);
            document.getElementById('total_amount').innerText='$'+total;
            document.getElementsByName('selected_user_ids')[0].value=selected_user_ids;
        }

        table.on( 'select', function ( e, dt, type, indexes ) {
            calc_total();
        } );
        table.on( 'deselect', function ( e, dt, type, indexes ) {
            calc_total();
        } );
        $("#credit_button").click(function(){
            $("#employer_payment_dlg_form").attr('action', "<?=url('Employer@employer_payment_credit_card');?>");
            $("#employer_payment_dlg_form").removeClass( "simcy-form" );
            $("#employer_payment_dlg_form").submit();
        });
    });
</script>
