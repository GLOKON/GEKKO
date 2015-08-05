<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>
			@section('title')
			@show
		</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="author" content="Daniel McAssey" />
		<meta name="robots" content="all, index, follow" />
		<meta name="revisit-after" content="7 days" />
		<meta name="Rating" content="General" />
		<meta name="description" content="GEKKO URL Shortener" />

		<link rel="apple-touch-icon" sizes="180x180" href="{{{ asset('/packages/glokon/gekko/assets/ico/apple-touch-icon-180x180.png', Config::get("gekko::gekko.https")) }}}" />
		<link rel="apple-touch-icon" sizes="152x152" href="{{{ asset('/packages/glokon/gekko/assets/ico/apple-touch-icon-152x152.png', Config::get("gekko::gekko.https")) }}}" />
		<link rel="apple-touch-icon" sizes="144x144" href="{{{ asset('/packages/glokon/gekko/assets/ico/apple-touch-icon-144x144.png', Config::get("gekko::gekko.https")) }}}" />
		<link rel="apple-touch-icon" sizes="120x120" href="{{{ asset('/packages/glokon/gekko/assets/ico/apple-touch-icon-120x120.png', Config::get("gekko::gekko.https")) }}}" />
		<link rel="apple-touch-icon" sizes="114x114" href="{{{ asset('/packages/glokon/gekko/assets/ico/apple-touch-icon-114x114.png', Config::get("gekko::gekko.https")) }}}" />
		<link rel="apple-touch-icon" sizes="76x76" href="{{{ asset('/packages/glokon/gekko/assets/ico/apple-touch-icon-76x76.png', Config::get("gekko::gekko.https")) }}}" />
		<link rel="apple-touch-icon" sizes="72x72" href="{{{ asset('/packages/glokon/gekko/assets/ico/apple-touch-icon-72x72.png', Config::get("gekko::gekko.https")) }}}" />
		<link rel="apple-touch-icon" sizes="60x60" href="{{{ asset('/packages/glokon/gekko/assets/ico/apple-touch-icon-60x60.png', Config::get("gekko::gekko.https")) }}}">
		<link rel="apple-touch-icon" sizes="57x57" href="{{{ asset('/packages/glokon/gekko/assets/ico/apple-touch-icon-57x57.png', Config::get("gekko::gekko.https")) }}}" />
		<link rel="icon" type="image/png" href="{{{ asset('/packages/glokon/gekko/assets/ico/favicon-194x194.png', Config::get("gekko::gekko.https")) }}}" sizes="194x194">
		<link rel="icon" type="image/png" href="{{{ asset('/packages/glokon/gekko/assets/ico/android-chrome-192x192.png', Config::get("gekko::gekko.https")) }}}" sizes="192x192">
		<link rel="icon" type="image/png" href="{{{ asset('/packages/glokon/gekko/assets/ico/favicon-96x96.png', Config::get("gekko::gekko.https")) }}}" sizes="96x96">
		<link rel="icon" type="image/png" href="{{{ asset('/packages/glokon/gekko/assets/ico/favicon-32x32.png', Config::get("gekko::gekko.https")) }}}" sizes="32x32">
		<link rel="icon" type="image/png" href="{{{ asset('/packages/glokon/gekko/assets/ico/favicon-16x16.png', Config::get("gekko::gekko.https")) }}}" sizes="16x16">
		<link rel="manifest" href="{{{ asset('/packages/glokon/gekko/assets/ico/manifest.json', Config::get("gekko::gekko.https")) }}}">
		<meta name="msapplication-TileColor" content="#4580c2">
		<meta name="msapplication-TileImage" content="{{{ asset('/packages/glokon/gekko/assets/ico/mstile-144x144.png', Config::get("gekko::gekko.https")) }}}">
		<meta name="msapplication-config" content="{{{ asset('/packages/glokon/gekko/assets/ico/browserconfig.xml', Config::get("gekko::gekko.https")) }}}">
		<meta name="theme-color" content="#4580c2">

		<link rel="stylesheet" type="text/css" href='https://fonts.googleapis.com/css?family=Lato|IM+Fell+English+SC|Roboto' />
		<link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">

		<link rel="stylesheet" type="text/css" href="{{ asset('/packages/glokon/gekko/bootstrap/css/bootstrap.min.css', Config::get("gekko::gekko.https")) }}" />
		<link rel="stylesheet" type="text/css" href="{{ asset('/packages/glokon/gekko/assets/css/font-awesome.min.css', Config::get("gekko::gekko.https")) }}" />

		{{ Minify::stylesheet(array('/packages/glokon/gekko/assets/css/start.css', '/packages/glokon/gekko/assets/css/app.css')) }}
@if(Auth::check())
	@if(Auth::user()->is_admin)
		<!-- ADMIN CSS -->
		{{ Minify::stylesheet('/packages/glokon/gekko/assets/css/admin.css') }}
	@endif
@endif
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->

		<script type="text/javascript">
			(function(w,d,u){w.readyQ=[];w.bindReadyQ=[];function p(x,y){if(x=="ready"){w.bindReadyQ.push(y);}else{w.readyQ.push(x);}};var a={ready:p,bind:p};w.$=w.jQuery=function(f){if(f===d||f===u){return a}else{p(f)}}})(window,document)
		</script>

		<script type="text/javascript">
			$( document ).ready(function() {
@if(Auth::check())
				InitializeApp();
	@if(Auth::user()->is_admin)
				InitializeAdminApp();
	@endif
@endif
			});
		</script>

	</head>
	<body>
		<div id="content">
			<div class="container-fluid">
				<div id="header">
					<div id="header-logo" class="col-md-12 col-xs-18">
						<?php
							$header_img = HTML::image('/packages/glokon/gekko/assets/img/logo.png', Lang::get('gekko::site.title'), array('class' => 'img-responsive'), Config::get("gekko::gekko.https"));
						?>
						<a href="{{ URL::to('/'.Config::get("gekko::gekko.path").'/', null, Config::get("gekko::gekko.https")) }}" title="{{ Lang::get('gekko::site.title') }}" >{{ $header_img }}</a>
					</div>
				</div>
				<noscript>
					<div class="col-md-12 col-xs-18">
						<div class="alert alert-danger text-center" role="alert">
							<i class="fa fa-exclamation-triangle"></i>&nbsp;<strong>{{{ Lang::get('gekko::site.gen_error_caps') }}}:</strong>&nbsp;{{{ Lang::get('gekko::site.no_js') }}}
						</div>
					</div>
				</noscript>
				<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
					@include('gekko::notifications')
				</div>
				<div class="row">
@if(Auth::check())
					<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
						<div class="panel panel-info" >
							<div class="panel-heading clearfix">
								<h4 class="panel-title pull-left" style="padding-top: 7.5px;">{{{ Lang::get('gekko::site.index_welcome') }}}&nbsp;<strong>{{{ Auth::user()->username }}}</strong></h4>
								<div id="btns_controls" class="btn-group pull-right samewidth">
									<button type="button" data-toggle="modal" data-target="#modal_shorten_url" class="btn btn-success btn-sm" title="{{{ Lang::get('gekko::site.index_shorten_url') }}}"><i class="fa fa-plus"></i><span class="bs-hidden btn_hidden_txt">&nbsp;{{{ Lang::get('gekko::site.index_shorten_url') }}}</span></button>
									<button type="button" data-toggle="modal" data-target="#modal_api_key" class="btn btn-primary btn-sm" title="{{{ Lang::get('gekko::site.index_show_api_key') }}}"><i class="fa fa-key"></i><span class="bs-hidden btn_hidden_txt">&nbsp;{{{ Lang::get('gekko::site.index_show_api_key') }}}</span></button>
									<button type="button" data-toggle="modal" data-target="#modal_profile" class="btn btn-info btn-sm" title="{{{ Lang::get('gekko::site.index_profile') }}}"><i class="fa fa-user"></i><span class="bs-hidden btn_hidden_txt">&nbsp;{{{ Lang::get('gekko::site.index_profile') }}}</span></button>
									<button type="button" data-toggle="modal" data-target="#modal_change_pw" class="btn btn-warning btn-sm" title="{{{ Lang::get('gekko::site.index_change_password') }}}"><i class="fa fa-unlock-alt"></i><span class="bs-hidden btn_hidden_txt">&nbsp;{{{ Lang::get('gekko::site.index_change_password') }}}</span></button>
									<button id="btn_logout" type="button" class="btn btn-danger btn-sm" title="{{{ Lang::get('gekko::site.logout') }}}"><i class="fa fa-times"></i><span class="bs-hidden btn_hidden_txt">&nbsp;{{{ Lang::get('gekko::site.logout') }}}</span></button>
								</div>
							@if(Auth::user()->is_admin)
								<div id="btns_admin" class="btn-group pull-right samewidth">
									<button id="btn_admin" type="button" class="btn btn-danger btn-sm" title="{{{ Lang::get('gekko::site.index_admin') }}}"><i class="fa fa-cog"></i></button>
								</div>
							@endif
							</div>
							<div class="panel-body">
@endif
	@yield('content')
@if(Auth::check())
							</div>
						</div>
					</div>
	@include('gekko::modals.shortenlink')
	@include('gekko::modals.apikey')
	@include('gekko::modals.changepw')
	@include('gekko::modals.editprofile')
@endif
				</div>
			</div>
		</div>

		<div id="footer" class="container">
			<section class="footer-body">
				<ul class="glokon-footer">
					<li>
						{{{ Lang::get('gekko::site.title') }}}
						<span id="footer-build-information">// URL Shortener</span>
					</li>
				</ul>
				<div id="footer-logo"><a href="http://www.glokon.me/" target="_blank">GLOKON</a></div>
			</section>
		</div>

		<script tpye="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script tpye="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
		<script tpye="text/javascript">(function($,d){$.each(readyQ,function(i,f){$(f)});$.each(bindReadyQ,function(i,f){$(d).bind("ready",f)})})(jQuery,document)</script>
		<script tpye="text/javascript" src="{{ asset('/packages/glokon/gekko/bootstrap/js/bootstrap.min.js', Config::get("gekko::gekko.https")) }}"></script>
@if(Auth::check())
		{{ Minify::javascript('/packages/glokon/gekko/assets/js/app.js') }}
	@if(Auth::user()->is_admin)
		<!-- ADMIN SCRIPT -->
		{{ Minify::javascript('/packages/glokon/gekko/assets/js/admin.js') }}
	@endif
@endif
@yield('scripts')
	</body>
</html>