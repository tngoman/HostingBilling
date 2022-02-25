<?php
$info = Payment::view_by_id($id);
?><div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-danger"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=lang('refunded')?> - <?=$info->currency?> <?=$info->amount?></h4>
		</div><?php
			echo form_open(base_url().'payments/refund'); ?>
		<div class="modal-body">
			<p><?=lang('refund_payment_warning')?></p>
			
			<input type="hidden" name="id" value="<?=$id?>">

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-danger"><?=lang('delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->