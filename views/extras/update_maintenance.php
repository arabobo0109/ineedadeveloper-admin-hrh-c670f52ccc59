	<div class="modal-body">
		<p>{{$request->content}}</p>
		@if($request->image)
		<img src="<?=url("")?>uploads/maintenance/{{ $request->image }}" class="img-responsive click-preview">
		@endif
		<div class="timeline">
			@if ( count($comments) > 0 )
			<div class="circle"></div>
			<ul>
				@foreach ( $comments as $history )
				<li class="{{$history->internal}}">
					<em class="text-xs">{{ setToUserTimezone($history->created_at) }}
						&nbsp;&nbsp;<span class="text-primary" style="font-size: 12px">{{ $history->author_name }}</span>
					</em>
					{{ $history->content }}
                    @if($history->image)
                    <img src="<?=url("")?>uploads/maintenance/{{ $history->image }}" class="img-responsive click-preview">
                    @endif
				</li>
				@endforeach
			</ul>
			<div class="circle"></div>
			@endif
		</div>
		<hr>
        @if($user->role != 'user')
		<label>Select a status of the request</label><label class="color-red">*</label>
		<div class="form-group">
			@foreach($status_list as $status )
			<div class="row">
				<div class="col-md-12">
					<label class="radio"><input type="radio" name="status" value="{{$status}}" {{$request->status==$status?'checked':''}}><span class="outer"><span class="inner"></span></span>{{$status}}</label>
				</div>
			</div>
			@endforeach
		</div>
        @endif
		<div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label>Reply Details</label><label class="color-red">*</label>
                </div>
                @if($user->role != 'user')
                <div class="col-md-6">
                    Internal ?
                    <input type="checkbox" class="switch" value="1" name="internal"/>
                </div>
                @endif
            </div>
			<div class="row">
				<div class="col-md-12">
					<textarea class="form-control" name="content" rows="4" ></textarea>
				</div>
                <div class="col-md-12">
                    <input type="file" name="maintenance_image" class="croppie" accept="image/*" crop-width="1000" crop-height="700" >
                </div>
            </div>
		</div>
	</div>
	<div class="modal-footer">
		<input type="hidden" name="csrf-token" value="{{ csrf_token(); }}" />
		<input type="hidden" name="id" value="{{ $request->id }}" />
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save</button>
	</div>

    <script type="text/javascript">
        // initialize Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.switch'));
        elems.forEach(function(html) {
            var switchery = new Switchery(html, {
                size: 'small',
                color: '#007bff'
            });
        });
        // croppify
        croppify();
    </script>