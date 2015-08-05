@extends('layouts.user')
{{-- Web site Title --}}
@section('title')
{{{ Lang::get('gekko::site.title_index') }}} - {{{ Lang::get('gekko::site.title') }}}
@parent
@stop

{{-- Content --}}
@section('content')

<div class="col-md-12">
<?php
	$__QUOTA_WARN_LEVEL = 65;
	$__QUOTA_DANGER_LEVEL = 90;

	$__QUOTA_USED = \Auth::user()->quota_used;
	$__QUOTA_MAX = \Auth::user()->quota_max;
	$__QUOTA_PERCENTAGE_USED = 0.0;
	if($__QUOTA_MAX == 0)
	{
		$__QUOTA_PERCENTAGE_USED = 0;
		$__QUOTA_MAX = '&#8734;';
	}
	else
	{
		$__QUOTA_PERCENTAGE_USED = ($__QUOTA_USED / $__QUOTA_MAX) * 100;
	}
	$__QUOTA_BAR_WIDTH_0 = ($__QUOTA_PERCENTAGE_USED >= $__QUOTA_WARN_LEVEL ? $__QUOTA_WARN_LEVEL : $__QUOTA_PERCENTAGE_USED);
	$__QUOTA_BAR_WIDTH_1 = ($__QUOTA_PERCENTAGE_USED <= $__QUOTA_WARN_LEVEL ? 0 : ($__QUOTA_PERCENTAGE_USED >= $__QUOTA_DANGER_LEVEL ? ($__QUOTA_DANGER_LEVEL - $__QUOTA_WARN_LEVEL) : $__QUOTA_PERCENTAGE_USED - $__QUOTA_WARN_LEVEL));
	$__QUOTA_BAR_WIDTH_2 = ($__QUOTA_PERCENTAGE_USED <= $__QUOTA_DANGER_LEVEL ? 0 : $__QUOTA_PERCENTAGE_USED - $__QUOTA_DANGER_LEVEL);

?>
	<div class="clearfix">
		<div class="pull-left">
			<strong>{{{ Lang::get('gekko::site.quota_used') }}} [{{ number_format($__QUOTA_USED) }}/{{ is_int($__QUOTA_MAX) ? number_format($__QUOTA_MAX) : $__QUOTA_MAX }}]</strong>
		</div>
		<div class="pull-right">
			<strong>{{ number_format($__QUOTA_PERCENTAGE_USED, 2) }}%</strong>
		</div>
	</div>
	<div class="progress">
		<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ $__QUOTA_BAR_WIDTH_0 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $__QUOTA_BAR_WIDTH_0 }}%">
			<span class="sr-only">{{ $__QUOTA_BAR_WIDTH_0 }}% {{{ Lang::get('gekko::site.quota_used_short') }}}</span>
		</div>
		<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="{{ $__QUOTA_BAR_WIDTH_1 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $__QUOTA_BAR_WIDTH_1 }}%">
			<span class="sr-only">{{ $__QUOTA_BAR_WIDTH_1 }}% {{{ Lang::get('gekko::site.quota_used_short') }}} [{{{ Lang::get('gekko::site.gen_notice_caps') }}}]</span>
		</div>
		<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="{{ $__QUOTA_BAR_WIDTH_2 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $__QUOTA_BAR_WIDTH_2 }}%">
			<span class="sr-only">{{ $__QUOTA_BAR_WIDTH_2 }}% {{{ Lang::get('gekko::site.quota_used_short') }}} [{{{ Lang::get('gekko::site.gen_warn_caps') }}}]</span>
		</div>
	</div>
</div>

<table id="link_list" class="table table-hover">
	<thead>
		<th>{{{ Lang::get('gekko::site.link_th_code') }}}</th>
		<th>{{{ Lang::get('gekko::site.link_th_clicks') }}}</th>
		<th>{{{ Lang::get('gekko::site.link_th_dest') }}}</th>
		<th>{{{ Lang::get('gekko::site.link_th_actions') }}}</th>
	</thead>
	<tbody>
		<?php $lastLinkID = $lastShortenedLinkID; ?>
		@foreach ($shortenedLinks as $selectedLink)
		<tr data-rowID="{{{ $selectedLink->id }}}" data-linkCode="{{{ $selectedLink->code }}}">
			<td><a href="{{ URL::to('/'.$selectedLink->code, null, Config::get("gekko::gekko.https")) }}" target="_blank" >{{{ $selectedLink->code }}}</a></td>
			<td>{{{ $selectedLink->clicks }}}</td>
			<td>{{{ $selectedLink->destination }}}</td>
			<td>
				<div class="btn-group">
					<button type="button" id="link_view" class="btn btn-success btn-sm" data-id="{{{ $selectedLink->id }}}" data-loading-text="<i class='fa fa-spinner fa-spin'></i> {{{ Lang::get('gekko::site.link_view_loading') }}}"><i class="fa fa-eye"></i>&nbsp;{{{ Lang::get('gekko::site.link_view') }}}</button>
					<button type="button" id="link_delete" class="btn btn-danger btn-sm" data-id="{{{ $selectedLink->id }}}" data-loading-text="<i class='fa fa-spinner fa-spin'></i> {{{ Lang::get('gekko::site.link_delete_loading') }}}"><i class="fa fa-times"></i>&nbsp;{{{ Lang::get('gekko::site.link_delete') }}}</button>
				</div>
			</td>
		</tr>
		<?php if($selectedLink->id > $lastLinkID) { $lastLinkID = $selectedLink->id; } ?>
		@endforeach
	</tbody>
</table>
<div id="lastLinkID" class="hide" data-linkid="{{{ $lastLinkID }}}"></div>
<div class="text-center">
	{{ $shortenedLinks->links() }}
</div>

@stop