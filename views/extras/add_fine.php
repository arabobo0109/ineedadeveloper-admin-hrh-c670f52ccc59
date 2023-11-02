<div class="modal-header">
    <h4 class="modal-title">Inspect Room ( {{ $room_name }} )</h4>
</div>
<div class="modal-body" style="height: 100%">
    <div class="form-group">
        <label>Fine Fees</label> (To edit fees, Please go to 'Fees' in sidebar menu)
        @if ( count($fine_fees) > 0 )
        @foreach ( $fine_fees as $index => $fine_fee )
            <div class="row fine_item" style="margin-top: 2px;">
                <div class="col-md-12">
                    <input type="checkbox" class="switch"  name="fine_ids[]" value="{{ $fine_fee->id }}" >
                    {{ $fine_fee->type }} - ${{ $fine_fee->amount }}
                </div>
            </div>
        @endforeach
        @endif
    </div>
    <button class="btn" type="button" data-toggle="collapse" data-target="#create_fine_div"><i class="ion-plus-round"></i>Create New Fine</button>
    <div id="create_fine_div" class="collapse">
        <div class="panel panel-default" style="padding: 20px 15px 0px;
    margin: 5px 30px 0px;">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6 col-md-offset-2">
                        <label>Fine type</label>
                        <input type="text" class="form-control" id="fine_type" placeholder="Fine type">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6 col-md-offset-2">
                        <label>Fine Amount($)</label>
                        <input type="number" class="form-control" id="fine_amount" placeholder="Fine Amount">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#create_fine_div">Close</button>
                <button type="button" class="btn btn-success" onclick="onAddFine()">Create Fine</button>
            </div>
        </div>
    </div>
    <hr>
    <div class="form-group" style="margin-top: 20px">
        <label>Inspector Note : &nbsp;</label>
        <textarea name="note" style="width: 98%;"></textarea>
    </div>
    <input type="hidden" name="csrf-token" value="{{ csrf_token(); }}"/>
    <input type="hidden" name="user_id" value="{{ $user->id }}"/>
    <div class="form-group">
        <label>
            *To cancel a fine, please go to 'Balance History' and cancel.
        </label>
        <label>
            *To add negative Credit amount, Please go to 'Payment' Page and pay with 'Credit'
        </label>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add Fine</button>
    </div>

</div>
<script>
    let baseUrl = '<?=url("");?>';
    let csrf = '<?=csrf_token();?>';
    function onAddFine(){
        var fine_type = $( "#fine_type" ).val();
        var fine_amount = $( "#fine_amount" ).val();
        if(fine_type == ""){
            swal("Error!", "You have to Input Fine Type", "error");
        }
        else if(fine_amount == ""){
            swal("Error!", "You have to input Fine Amount", "error");
        }
        else{
            $.ajax({
                url: baseUrl + 'fine/createByAjax',
                type: "post",
                data: {
                    type : $( "#fine_type" ).val(),
                    amount : $( "#fine_amount" ).val(),
                    'csrf-token': csrf
                },
                success: function(data){
                    console.log(data);
                    swal("Fine!", "New Fine was Added", "success");
                    let first_item=$(".fine_item").first();
                    let clone=first_item.clone();
                    clone.find('.col-sm-1').val(data.id);
                    clone.find('.col-sm-9').text(data.type+' - $'+data.amount);
                    first_item.parent().append(clone);
                    console.log(data);
                }
            });
        }

    }
</script>