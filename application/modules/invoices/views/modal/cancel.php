<?php
$i = Invoice::view_by_id($id);
?>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-danger"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('cancel')?> <?=lang('invoice')?> #<?=$i->reference_no?></h4>
		</div><?php
			echo form_open(base_url().'invoices/cancel'); ?>
		<div class="modal-body">
			<p>Invoice <?=$i->reference_no?> will be marked as Cancelled.</p>

			<input type="hidden" name="id" value="<?=$id?>">

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-danger"><?=lang('cancelled')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
