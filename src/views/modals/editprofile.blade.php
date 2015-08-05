<!-- Modal: Edit Profile -->
<div id="modal_profile" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><i class="fa fa-user"></i>&nbsp;{{{ Lang::get('gekko::site.modal_title_profile') }}}</h4>
			</div>
			{{ Form::open(array('url' => url('manage/ajax/user/updateProfile', null, Config::get("gekko::gekko.https")), 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form_edit_profile', 'role' => 'form')) }}
			<div class="modal-body">
				<div id="error_box" class="alert-message alert-message-danger bs-hidden">
					<h4><i class="fa fa-info-circle"></i>&nbsp;<strong>{{{ Lang::get('gekko::site.gen_error_caps') }}}</strong></h4>
					<span id="message"></span>
				</div>
				<div id="success_box" class="alert-message alert-message-info bs-hidden">
					<h4><i class="fa fa-info-circle"></i>&nbsp;<strong>{{{ Lang::get('gekko::site.gen_success_caps') }}}</strong></h4>
					<span id="message"></span>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
							{{ Form::text('email', Auth::user()->email, array('class' => 'form-control', 'placeholder' => Lang::get('gekko::site.modal_profile_email'), 'label' => Lang::get('gekko::site.modal_profile_email'))) }}
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button id="btn_edit_profile" type="button" class="btn btn-primary" data-loading-text="<i class='fa fa-cog fa-spin'></i> {{{ Lang::get('gekko::site.modal_profile_save_loading') }}}">{{{ Lang::get('gekko::site.modal_profile_save') }}}</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">{{{ Lang::get('gekko::site.modal_close') }}}</button>
			</div>
			{{ Form::close() }}
		</div>
	</div>
</div>