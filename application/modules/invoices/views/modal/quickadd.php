<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('add_item')?></h4>
		</div>
		<?php
			$attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'invoices/items/insert',$attributes); ?>
          <input type="hidden" name="invoice" value="<?=$invoice?>">

		<div class="modal-body">

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('item_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="item" class="form-control" required="required">
				<option value=""><?=lang('choose_template')?></option>
					<?php foreach (Invoice::saved_items() as $key => $item) { ?>
						<option value="<?=$item->item_id?>"><?=$item->item_name?> - <?=$item->unit_cost?></option>
					<?php } ?>					
				</select>
				</div>
				</div>


				

			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-<?=config_item('theme_color')?>"><?=lang('add_item')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->