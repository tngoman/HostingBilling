<div class="modal-dialog modal-sm">
	<div class="modal-content">
		<div class="modal-header bg-<?=config_item('theme_color');?>"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=ucfirst(lang('login_details'))?></h4>
		</div> 
		<div class="modal-body">
		 <table class="table table-bordered">
		 	<thead><tr><th><?=lang('username')?></th><th><?=lang('password')?></th></tr></thead>
			 <tbody><tr><td><?=$item->username?></td><td><?= (Applib::is_demo()) ? 'Hidden in demo' : $item->password?></td></tr></tbody>
		</table>
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->