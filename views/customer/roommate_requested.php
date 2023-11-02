{{ view("includes/head", $data); }}
<body>
@if (isset($user))
{{ view("includes/header", $data); }}
{{ view("includes/sidebar", $data); }}
@endif
<div class="content">
    @if (isset($user))
    <?php include "profile_header.php" ?>
    @endif
    <form class="simcy-form" action="<?=url("Student@updateRoommate");?>" method="POST" data-parsley-validate="" loader="true" >
        @if (!isset($user))
        <input type="hidden" name="from" value="docsign">
        @endif
        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">
        <input type="hidden" name="user_id" value="{{ $student->id }}">
                <div class="form-group">
                    <h4>Employer: {{$student->employer_name}}</h4>
                    <h3>Roommate Request (If you have roommates)</h3>
                    <p style="font-size: 18px">***Please remember that all roommate requests are up to the discretion of management and will be accommodated to the best of our ability based upon occupancy.***</p>
            @for ($i=0;$i<3;$i++)
            <input type="hidden" class="form-control" name="id[]" value="{{ $roommates[$i]->id }}">
                <div class="row">
                <br>
                <h4> Roommate {{$i+1}}</h4>
                <div class="col-md-3">
                    <label>First Name<label class="color-red">*</label></label>
                    <input type="text" class="form-control" name="fname[]" value="{{ $roommates[$i]->fname }}" placeholder="First Name">
                </div>
                <div class="col-md-3">
                    <label>Last Name</label>
                    <input type="text" class="form-control" name="lname[]" value="{{ $roommates[$i]->lname }}" placeholder="Last Name">
                </div>
                <div class="col-md-3">
                    <label>Employer name<label class="color-red">*</label></label>
                    <input type="text" class="form-control" name="business_name[]" value="{{ $roommates[$i]->business_name }}" placeholder="Employer name">
                </div>
                    <div class="col-md-3">
                        <label>Arrival Date<label class="color-red">*</label></label>
                        <input type="text" class="form-control" name="lease_start[]" value="{{ $roommates[$i]->lease_start!='0000-00-00'?$roommates[$i]->lease_start:'' }}" placeholder="Arrival Date" >
                    </div>
            </div>
            @endfor
        </div>

        <hr>
        <div style="width: 70%; margin: 15px auto;">
            @if (!isset($user))
            <button class="btn" onclick="window.location.replace (location.origin+'/profilePicture/passport');">Skip</button>
            @endif
            <button class="btn btn-primary" type="submit" style="float: right;">Save</button>
        </div>
    </form>
</div>

<!-- footer -->
{{ view("includes/footer"); }}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script>
    $(document).ready(function() {
        $('input[name="lease_start[]"]').datepicker({
            todayHighlight: !0,
            format: 'yyyy-mm-dd'
        });
    });
</script>

</body>
</html>