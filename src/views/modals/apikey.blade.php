<!-- Modal: API Key -->
<div id="modal_api_key" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><i class="fa fa-key"></i>&nbsp;{{{ Lang::get('gekko::site.modal_title_api_key') }}}</h4>
			</div>
			<div class="modal-body">
				<div class="alert-message alert-message-warning">
					<h4><i class="fa fa-exclamation-triangle"></i>&nbsp;<strong>{{{ Lang::get('gekko::site.gen_warn_caps') }}}</strong></h4>
					<p>{{{ Lang::get('gekko::site.modal_api_key_warning') }}}</p>
				</div>
				<div id="api_key_area">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-key"></i></span>
						{{ Form::text('api_key_input', '', array('id' => 'api_key_input', 'class' => 'form-control', 'readonly', 'placeholder' => Lang::get('gekko::site.modal_api_key_hidden'))) }}
					</div>
					<a href="#" id="api_key_selectall">{{{ Lang::get('gekko::site.select_all') }}}</a>
				</div>
			</div>
			<div class="modal-footer">
				<button id="btn_show_api_key" type="button" class="btn btn-primary" data-loading-text="<i class='fa fa-cog fa-spin'></i> {{{ Lang::get('gekko::site.modal_api_key_show_key_loading') }}}">{{{ Lang::get('gekko::site.modal_api_key_show_key') }}}</button>
				<button id="btn_gen_api_key" type="button" class="btn btn-success" data-loading-text="<i class='fa fa-cog fa-spin'></i> {{{ Lang::get('gekko::site.modal_api_key_gen_key_loading') }}}">{{{ Lang::get('gekko::site.modal_api_key_gen_key') }}}</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">{{{ Lang::get('gekko::site.modal_close') }}}</button>
			</div>
		</div>
	</div>
</div>