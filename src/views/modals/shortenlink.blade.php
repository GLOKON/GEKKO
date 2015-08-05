<!-- Modal: Upload File -->
<div id="modal_shorten_url" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><i class="fa fa-plus"></i>&nbsp;{{{ Lang::get('gekko::site.modal_title_shorten_url') }}}</h4>
			</div>
			{{ Form::open(array('url' => url('manage/ajax/link/shorten', null, Config::get("gekko::gekko.https")), 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form_shorten_url', 'role' => 'form')) }}
			<div class="modal-body">
				<div id="error_box" class="alert-message alert-message-danger bs-hidden">
					<h4><i class="fa fa-info-circle"></i>&nbsp;<strong>{{{ Lang::get('gekko::site.gen_error_caps') }}}</strong></h4>
					<span id="message"></span>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-link"></i></span>
							{{ Form::text('url_input', '', array('id' => 'shorten_url_input', 'class' => 'form-control', 'placeholder' => Lang::get('gekko::site.modal_shorten_url'), 'label' => Lang::get('gekko::site.modal_shorten_url'))) }}
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-external-link-square"></i></span>
							{{ Form::text('url_output', '', array('id' => 'shorten_url_output', 'class' => 'form-control', 'readonly', 'placeholder' => Lang::get('gekko::site.modal_shorten_result'))) }}
						</div>
						<a href="#" id="shorten_url_selectall">{{{ Lang::get('gekko::site.select_all') }}}</a>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button id="btn_shorten_url" type="button" class="btn btn-primary" data-loading-text="<i class='fa fa-cog fa-spin'></i> {{{ Lang::get('gekko::site.modal_shorten_submit_loading') }}}">{{{ Lang::get('gekko::site.modal_shorten_submit') }}}</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">{{{ Lang::get('gekko::site.modal_close') }}}</button>
			</div>
			{{ Form::close() }}
		</div>
	</div>
</div>