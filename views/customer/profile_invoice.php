<div class="light-card table-responsive">
    <h4 class="text-align-center"> Transaction History</h4>
    <table class="table display" id="data-table">
        <thead>
        <tr>
            <th>Time</th>
            <th>Amount</th>
            <th>Type</th>
            <th>Number</th>
            <th>Where</th>
            @if($user->role == "user")
                <th>Status</th>
            @else
                <th>Receipt</th><th>Refund</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach ( $invoice as $each_invoice )
        <tr>
            <td>{{date("Y-m-d H:i", strtotime($each_invoice->created_at))}}</td>
            <td><strong>${{ $each_invoice->price }}</strong></td>
            <td>{{$each_invoice->payment_mode}}</td>
            <td>{{$each_invoice->transaction_id}}</td>
            <td>
                @if ( $each_invoice->payment_option == 'by_user' )
                    <span class="label label-success">Online</span>
                @elseif ( $each_invoice->payment_option == 'by_admin' )
                    <span class="label label-info">Counter</span>
                @else
                    <span class="label label-warning">{{$each_invoice->payment_option}}</span>
                @endif
            </td>
            @if($user->role == "user")
                <td>{{$each_invoice->status}}</td>
            @else
                <td><a href="{{url('PrintController@invoicePrint').$each_invoice->id}}"  class="btn btn-success" style="padding: 1px 20px!important;">Print</a></td>
                <td>
                    @if ($each_invoice->status=='Refunded')
                        Refunded
                    @elseif($each_invoice->price>0 && $each_invoice->payment_mode!='Holding')
                        <button class="btn btn-primary refund_btn" style="padding: 1px 20px!important;" data-toggle="modal" data-target="#refund_dlg" data-backdrop="static" data-keyboard="false" data-amount="{{ $each_invoice->price }}" data-invoice="{{ $each_invoice->id }}"  data-id="0">Refund</button>
                    @else
                        {{$each_invoice->payment_option}}
                    @endif
                </td>
            @endif
        </tr>
        @endforeach
        </tbody>
    </table>
</div>

<!--{{ view("includes/fine_history", $data); }}-->