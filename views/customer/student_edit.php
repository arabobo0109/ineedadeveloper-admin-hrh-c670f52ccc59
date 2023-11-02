<div class="form-group">
    <div class="row">
        <div class="col-md-6 ">
            <label>First name </label> <label class="color-red">*</label>
            <input type="text" class="form-control" name="fname" value="{{ $student->fname }}" placeholder="First name" data-parsley-required="true">
            <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
        </div>
        <div class="col-md-6">
            <label>Last name</label> <label class="color-red">*</label>
            <input type="text" class="form-control" name="lname" value="{{ $student->lname }}" placeholder="Last name" data-parsley-required="true">
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label>Email</label> <label class="color-red">* </label> &nbsp; &nbsp;
            @if(!isset($student->email))
                <input type="checkbox" class="switch" value="1" id="print_lease"/>
                <label class="form-check-label">Print Lease</label>
                <input type="hidden" name="print_lease">
            @endif
            <input type="email" class="form-control" name="email" value="{{ $student->email }}" placeholder="Email"  data-parsley-required="true" >
        </div>
        <div class="col-md-6">
            <label>Phone number</label><label class="color-red">*</label><br>
            <select class="form-control phone_country" name="phone_country">
                <option value="US">US +1</option>
                @foreach($phone_code as $each_phone_code)
                    <option value="{{$each_phone_code->iso}}"
                            @if($student->phone_country == $each_phone_code->iso)
                        selected
                        @endif
                        >
                        {{$each_phone_code->iso}} +{{$each_phone_code->phonecode}}
                    </option>
                @endphp
            </select>
            <input type="text" class="form-control" name="phone" value="{{$student->phone}}" placeholder="Phone number" style="width: 64%" data-parsley-required="true">
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label>Gender</label>
            <select class="form-control" name="gender">
                <option value="Male" @if( !empty( $student->gender == "Male" ) ) selected @endif >Male</option>
                <option value="Female" @if( !empty( $student->gender == "Female" ) ) selected @endif >Female</option>
                <option value="Other" @if( !empty( $student->gender == "Other" ) ) selected @endif >Other</option>
            </select>
        </div>
        <div class="col-md-6">
            <label>Birthday</label><label class="color-red">*</label>
            <input type="text" class="form-control" name="birthday" value="{{ $student->birthday }}" autocomplete="off" placeholder="Birthday" data-parsley-required="true">
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label>Address</label>
            <input type="text" class="form-control" name="address" value="{{ $student->address }}" placeholder="Address" >
        </div>

        <div class="col-md-6">
            <label>City</label>
            <input type="text" class="form-control" name="city" value="{{ $student->city }}" placeholder="City">
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label>State</label>
            <input type="text" class="form-control" name="state" value="{{ $student->state }}" placeholder="state">
        </div>
        <div class="col-md-6">
            <label>Country</label><label class="color-red">*</label>
            <input type="text" class="form-control" name="country"  value="{{ isset($student->country)?$student->country:'United State' }}" placeholder="Country" data-parsley-required="true">
        </div>
    </div>
</div>
@if ($user->role!='user')
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label>Season</label>
            <select class="form-control" name="season">
                <option value="Autumn 2023" @if( !empty( $student->season == "Autumn 2023" ) ) selected @endif >Autumn 2023</option>
                <option value="Winter 2023" @if( !empty( $student->season == "Winter 2023" ) ) selected @endif >Winter 2023</option>
                <option value="Spring 2024" @if( !empty( $student->season == "Spring 2024" ) ) selected @endif >Spring 2024</option>
                <option value="Spring 2023" @if( !empty( $student->season == "Spring 2023" ) ) selected @endif >Spring 2023</option>
                <option value="Summer 2023" @if( !empty( $student->season == "Summer 2023" ) ) selected @endif >Summer 2023</option>
            </select>
        </div>
        <div class="col-md-6">
            <label>Identifier</label>
            <select class="form-control" name="identifier">
                <option>None</option>
                <option value="J" @if( !empty( $student->identifier == "J" ) ) selected @endif >J</option>
                <option value="H" @if( !empty( $student->identifier == "H" ) ) selected @endif >H</option>
                <option value="I" @if( !empty( $student->identifier == "I" ) ) selected @endif >I</option>
                <option value="J1" @if( !empty( $student->identifier == "J1" ) ) selected @endif >J1</option>
                <option value="U.S. Citizen" @if( !empty( $student->identifier == "U.S. Citizen" ) ) selected @endif >U.S. Citizen</option>
                <option value="H2B" @if( !empty( $student->identifier == "H2B" ) ) selected @endif >H2B</option>
            </select>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label>Employer</label> <label class="color-red">*</label>
            <select class="form-control select-employer" name="employer" data-parsley-required="true">
                <option value="0">None</option>
                @foreach ($employers as $each_employers )
                <option value="{{ $each_employers->id }}" @if( !empty( $student->employer == $each_employers->id ) ) selected @endif>{{ $each_employers->name }}</option>
                @endforeach
                <option value="other">Other</option>
            </select>
        </div>
        <div class="col-md-6">
            <label>Sponsor</label> <label class="color-red">*</label>
            <select class="form-control" name="sponsor"  data-parsley-required="true">
                @foreach ($enum as $sponsor )
                <option value="{{$sponsor}}" @if( !empty($student->sponsor == $sponsor ) ) selected @endif >{{$sponsor}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!--Create Employer-->
    <div id="create_employer" class="collapse" style="margin-top: 10px;">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="modal-title">Create Employer</h4>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12 ">
                            <label>Employer name</label>
                            <input type="text" class="form-control" id="employer_name" placeholder="Employer name" >
                            <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Company Information</label>
                            <input type="text" class="form-control" id="company_info" placeholder="Company Information" >
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Employer Email</label>
                            <input type="mail" class="form-control" id="employer_email" placeholder="Employer Email" >
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#create_employer">Close</button>
                <button type="button" id="save_employer" class="btn btn-primary" style="margin-right: 20px;">Save</button>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
@endif

