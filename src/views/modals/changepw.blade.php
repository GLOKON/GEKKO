<!-- Modal: Change Password -->
<div id="modal_change_pw" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><i class="fa fa-unlock-alt"></i>&nbsp;{{{ Lang::get('gekko::site.modal_title_change_pw') }}}</h4>
			</div>
			{{ Form::open(array('url' => url('manage/ajax/user/changePassword', null, Config::get("gekko::gekko.https")), 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form_change_pw', 'role' => 'form')) }}
			<div class="modal-body">
				<div id="error_box" class="alert-message alert-message-danger bs-hidden">
					<h4><i class="fa fa-info-circle"></i>&nbsp;<strong>{{{ Lang::get('gekko::site.gen_error_caps') }}}</strong></h4>
					<span id="message"></span>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-lock"></i></span>
							{{ Form::password('old_password', array('class' => 'form-control', 'placeholder' => Lang::get('gekko::site.modal_pw_old'), 'label' => Lang::get('gekko::site.modal_pw_old'))) }}
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-key"></i></span>
							{{ Form::password('new_password', array('class' => 'form-control', 'placeholder' => Lang::get('gekko::site.modal_pw_new'), 'label' => Lang::get('gekko::site.modal_pw_new'))) }}
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-key"></i></span>
							{{ Form::password('new_password_confirm', array('class' => 'form-control', 'placeholder' => Lang::get('gekko::site.modal_pw_new_confirm'), 'label' => Lang::get('gekko::site.modal_pw_new_confirm'))) }}
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button id="btn_change_pw" type="button" class="btn btn-primary" data-loading-text="<i class='fa fa-cog fa-spin'></i> {{{ Lang::get('gekko::site.modal_pw_change_loading') }}}">{{{ Lang::get('gekko::site.modal_pw_change') }}}</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">{{{ Lang::get('gekko::site.modal_close') }}}</button>
			</div>
			{{ Form::close() }}
		</div>
	</div>
</div>