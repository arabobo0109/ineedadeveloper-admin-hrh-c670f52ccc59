<div class="payment col-sm-6">
    <h4>{{ $result }}</h4>
    @if( $user->role !='user' )
        <a href="<?=url("Customer@profile");?>{{$student->id}}" class="btn btn-primary"  style="margin: 20px">Go to Student Details</a>
        @if( $student->invoice_id>0)
            <a href="{{url('PrintController@invoicePrint').$student->invoice_id}}"  class="btn btn-success" style="margin: 20px">Print Receipt</a>
        @endif
    @endif
</div>

<script>
@if($checkin_done == "done")
	setTimeout(function(){ logout(); }, 3000);
@endif

function logout(){
	location.replace("/signout");
}
</script>