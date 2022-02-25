<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-warning"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=lang('stop_recurring')?></h4>
		</div><?php
			echo form_open(base_url().'invoices/stop_recur'); ?>
		<div class="modal-body">
			<p><?=lang('stop_recur_warning')?></p>
			
			<input type="hidden" name="invoice" value="<?=$invoice?>">

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-success"><?=lang('delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->