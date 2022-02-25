<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('new_payment')?></h4>
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'invoices/pay',$attributes); ?>
		<div class="modal-body">
			<p><?=lang('payment_for_invoice')?></p>
			<input type="hidden" name="invoice_id" value="<?=$invoice_id?>">

          				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('trans_id')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<?php $this->load->helper('string'); ?>
					<input type="text" class="form-control" value="<?=random_string('nozero', 6);?>" name="trans_id" readonly>
				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('amount')?> (<?=$info->currency?>) <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=Applib::format_tax(Invoice::get_invoice_due_amount($invoice_id)); ?>" name="amount">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('payment_date')?></label>
				<div class="col-lg-8">
				<input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="payment_date" data-date-format="<?=config_item('date_picker_format');?>" >
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('payment_method')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="payment_method" class="form-control">
					<?php
					if (!empty($payment_methods)) {
					foreach ($payment_methods as $key => $p_method) { ?>
						<option value="<?=$p_method->method_id?>"><?=$p_method->method_name?></option>
					<?php } } ?>
				</select>
				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('notes')?></label>
				<div class="col-lg-8">
				<textarea name="notes" class="form-control ta"></textarea>
				</div>
				</div>

				<div class="form-group">
                      <label class="col-lg-4 control-label"><?=lang('send_email')?></label>
                      <div class="col-lg-8">
                        <label class="switch">
                          <input type="checkbox" name="send_thank_you">
                          <span></span>
                        </label>
                      </div>
                    </div>

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-success"><?=lang('add_payment')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
