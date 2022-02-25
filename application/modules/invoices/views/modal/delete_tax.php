<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-danger"> 
		<button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=lang('delete_rate')?></h4>
		</div><?php
			echo form_open(base_url().'invoices/tax_rates/delete'); ?>
		<div class="modal-body">
			<p><?=lang('delete_rate_warning')?></p>
			
			<input type="hidden" name="tax_rate_id" value="<?=$tax_rate_id?>">

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-<?=config_item('theme_color')?>"><?=lang('delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->