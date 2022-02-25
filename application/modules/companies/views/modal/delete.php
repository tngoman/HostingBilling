<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-danger"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=lang('delete_company')?></h4>
		</div><?php
			echo form_open(base_url().'companies/delete'); ?>
		<div class="modal-body">
			<p><?=lang('delete_company_warning')?></p>
			<ul>
				<li><?=lang('invoices')?></li>
				<li><?=lang('payments')?></li>
				<li><?=lang('activities')?></li>
			</ul>
			
			<input type="hidden" name="company" value="<?=$company_id?>">

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-danger"><?=lang('delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->