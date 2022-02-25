<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-<?=config_item('theme_color');?>"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=lang('change_password')?></h4>
		</div><?php
			echo form_open(base_url().'accounts/change_password'); ?>
		<div class="modal-body">
		<div class="row">
					<label class="control-label col-lg-3"><?=lang('new_password')?></label>
					<div class="col-lg-6">
						<input type="text" class="form-control" name="password" required="required">
						<input type="hidden" name="id" value="<?=$id?>">
					</div>
				</div>		
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-<?=config_item('theme_color');?>"><?=lang('change_password')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->