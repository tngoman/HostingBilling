<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=lang('edit_rate')?></h4>
		</div>
		<?php $r = Invoice::tax_by_id($id);?>

		<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'invoices/tax_rates/edit',$attributes); ?>
          <input type="hidden" name="tax_rate_id" value="<?=$r->tax_rate_id?>">
		<div class="modal-body">
			 
          				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('tax_rate_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" required value="<?=$r->tax_rate_name?>" name="tax_rate_name">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('tax_rate_percent')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control money" required value="<?=$r->tax_rate_percent?>" name="tax_rate_percent">
				</div>
				</div>

				
				
			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-<?=config_item('theme_color');?>"><?=lang('save_changes')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<script src="<?=base_url()?>resource/js/libs/jquery.maskMoney.min.js" type="text/javascript"></script>
	<script>
	(function($){
    		"use strict";
		    $('.money').maskMoney();
	})(jQuery);  

</script>