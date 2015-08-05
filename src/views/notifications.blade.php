@if ($message = Session::get('flash_success'))
<div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<h4><i class="fa fa-info-circle"></i>&nbsp;<strong>{{{ Lang::get('gekko::site.gen_success_caps') }}}</strong></h4>
	@if(is_array($message))
		@foreach ($message as $m)
			{{ $m }}
		@endforeach
	@else
		{{ $message }}
	@endif
</div>
@endif

@if ($message = Session::get('flash_error'))
<div class="alert alert-danger alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<h4><i class="fa fa-info-circle"></i>&nbsp;<strong>{{{ Lang::get('gekko::site.gen_error_caps') }}}</strong></h4>
	@if(is_array($message))
		@foreach ($message as $m)
			{{ $m }}
		@endforeach
	@else
		{{ $message }}
	@endif
</div>
@endif

@if ($message = Session::get('flash_warning'))
<div class="alert alert-warning alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<h4><i class="fa fa-exclamation-triangle"></i>&nbsp;<strong>{{{ Lang::get('gekko::site.gen_warn_caps') }}}</strong></h4>
	@if(is_array($message))
		@foreach ($message as $m)
			{{ $m }}
		@endforeach
	@else
		{{ $message }}
	@endif
</div>
@endif

@if ($message = Session::get('flash_notice'))
<div class="alert alert-info alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<h4><i class="fa fa-info-circle"></i>&nbsp;<strong>{{{ Lang::get('gekko::site.gen_success_caps') }}}</strong></h4>
	@if(is_array($message))
		@foreach ($message as $m)
			{{ $m }}
		@endforeach
	@else
		{{ $message }}
	@endif
</div>
@endif