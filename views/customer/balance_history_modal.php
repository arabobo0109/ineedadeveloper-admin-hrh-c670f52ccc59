<style>
    @media (min-width: 768px) {
        #view_balance_history .modal-dialog {
            width: 90%;
        }
    }
</style>
<!--View Balance History dialog -->
<div class="modal fade" id="view_balance_history" role="dialog" style="background-color: #fff;">
    <div class="close-modal" data-dismiss="modal">&times;</div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">View Balance History</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        Student Name : <label>{{ $student->fname }} {{ $student->lname }}</label>
                    </div>
                    <div class="col-md-6">
                        Room Number : <label>{{ $student->unit }}</label>
                    </div>
                </div>
                <div class="row padding-15">
                    <div class="col-md-3 padding-15 bal-dlg-col border-top border-left border-bottom">
                        <label>Total Balance Owed</label><br>
                        <span>$<span>{{$student->balance}}</span></span>
                    </div>
                    <div class="col-md-3 padding-15 bal-dlg-col border-top border-left border-bottom">
                        <label>Prepayments</label><br>
                        <span>$<span>{{$student->holding_balance}}</span></span>
                    </div>

                    <div class="col-md-3 padding-15 bal-dlg-col border-top border-left border-bottom border-right">
                        <label>Deposit Held</label><br>
                        <span>$<span>{{$student->depositHeld}}</span></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="light-card table-responsive p-b-3em">
                            <table class="table display companies-list" id="balance-table">
                                <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Note</th>
                                    <th>Made by</th>
                                    <th>Balance</th>
                                    <th>Paid Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($balance_history))
                                @foreach($balance_history as $item)
                                <tr>
                                    <td>{{ date('Y-m-d H:i',strtotime($item->created_at)) }}</td>
                                    <td><strong @if ($item->amount>0) class="color-red" @endif>${{ $item->amount }}</strong></td>
                                    <td>{{ $item->action }}</td>
                                    <td>{{ $item->note}}</td>
                                    <td>{{ $item->made_by}}</td>
<!--                                    <td @if ($item->owed_amount>0) class="color-red" @endif>${{ $item->owed_amount}}</td>-->
                                    <td>${{ $item->balance}}</td>
                                    <td>
                                        @if($item->invoice_id == 0)
                                            @if($item->paid_status=='Owed' && $item->owed_amount>0)
                                                <a class="send-to-server-click btn btn-primary" style="padding: 1px 20px!important;"  data="balance_id:{{ $item->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("Fine@cancel");?>" warning-title="Are you sure?" warning-message="The Fee will be canceled." warning-button="Continue" loader="true" href="">Cancel</a>
                                            @else
                                                {{$item->paid_status}}
                                            @endif
                                        @else
                                            <a href="{{url('PrintController@invoicePrint').$item->invoice_id}}"  class="btn btn-success" style="padding: 1px 20px!important;">Receipt</a>
                                            @if($item->paid_status == 'Paid' && $item->action!='Payment with Holding' && $item->amount<0)
                                                <button class="btn btn-primary refund_btn" style="padding: 1px 20px!important;" data-toggle="modal" data-target="#refund_dlg" data-backdrop="static" data-keyboard="false" data-amount="{{ -$item->amount }}" data-id="{{ $item->id }}" data-invoice="0">Refund</button>
                                            @else
                                                {{$item->paid_status}}
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="refund_dlg" role="dialog" style="background: white">
    <div class="close-modal" data-dismiss="modal">&times;</div>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content" >
            <div class="modal-header">
                <h4 class="modal-title">Partial Refund</h4>
            </div>
            <form class="simcy-form" id="edit-customer-form" action="<?=url("CC@refundInvoice");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="refund_balance_id" value="0" />
                <input type="hidden" name="invoice_id" value="0" />
                <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                <div class="modal-body" style="height: 150px;">
                    <p>Refund Amount should be between 1 and <label id="max_refund_amount">100</label></p>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Enter Amount(USD)</label>
                                <input type="number" class="form-control" name="refund_balance_amount" value="0" data-parsley-required="true" min="1" max="100">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Refund</button>
                </div>
            </form>
        </div>

    </div>
</div>