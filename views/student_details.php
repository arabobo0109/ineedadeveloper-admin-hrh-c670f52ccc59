<p>Fill in student's details, a signed email will be sent to the student.</p>

<?php include 'customer/student_edit.php'?>

<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            <label>Anticipate Move In Date<label class="color-red">*</label> and Move Out Date</label>
            <div class="input-group input-daterange">
                <input type="text" class="form-control" name="lease_start" placeholder="Move In Date "  data-parsley-required="true" autocomplete="off" value="{{(isset($student) && $student->lease_start) ? $student->lease_start : ''; ?>"/>
                <span class="input-group-addon">to</span>
                <input type="text" class="form-control" name="lease_end" placeholder="Move Out Date" autocomplete="off" value="{{(isset($student) && $student->lease_start) ? $student->lease_end : ''; ?>"/>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <input type="checkbox" class="switch" value="1" name="intern"/>
            <label class="form-check-label">intern</label>
        </div>
<!--        <div class="col-md-6">-->
<!--            <label>Lease type</label>-->
<!--            <select class="form-control" name="lease_type">-->
<!--                <option value="English" @if( !empty( $student->gender == "English" ) ) selected @endif >English</option>-->
<!--                <option value="Spanish" @if( !empty( $student->gender == "Spanish" ) ) selected @endif >Spanish</option>-->
<!--            </select>-->
<!--        </div>-->
        <div class="col-md-6">
            <label>Security Deposit</label>
            <input type="number" class="form-control compare_sec_input" name="initial_security_fee" value="{{$company_security}}" placeholder="{{$company_security}}" pattern="^\d*(\.\d{0,2})?$">
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label>Zip code</label>
            <input type="text" class="form-control" name="zip" value="{{ $student->zip }}" placeholder="zip code" pattern="^\s*?\d{5}(?:[-\s]\d{4})?\s*?$">
        </div>
        <div class="col-md-6">
            <label>Account Type</label>
            <label class="color-red">*</label>
            <select class="form-control" name="account_type">
                <option value="Real" @if( !empty( $student->account_type == "Real" ) ) selected @endif >Real</option>
                <option value="Test" @if( !empty( $student->account_type == "Test" ) ) selected @endif >Test</option>
            </select>
        </div>
    </div>
</div>

<div class="form-group hidden">
    <div class="row">
        <div class="col-md-6">
            <input type="checkbox" class="switch" id="weekly_rate_check" />
            <label class="form-check-label">Specific Weekly Rate</label>
        </div>
        <div class="col-md-6">
            @if($student->weekly_rate == null)
            <input type="number" class="form-control compare_weekly_input" name="weekly_rate" id="weekly_rate" value="{{$company_weekly}}" style="display: none;">
            @else
            <input type="number" class="form-control compare_weekly_input" name="weekly_rate" id="weekly_rate" value="{{ $student->weekly_rate }}" style="display: none;">
            @endif
            <label class="color-red compare_weekly_label" style="display:none;">Weekly Rate Changed for student</label>
        </div>
    </div>
</div>
<!--<div class="form-group">-->
<!--    <div class="row">-->
<!--        <div class="col-md-12">-->
<!--            <label>Miscellaneous notes</label>-->
<!--            <textarea class="form-control" name="extra_note" rows="3"-->
<!--                      placeholder="Miscellaneous notes">{{ $student->extra_note }}</textarea>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

