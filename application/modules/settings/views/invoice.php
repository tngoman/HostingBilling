	<!-- Start Form -->
 		<?php
		$attributes = array('class' => 'bs-example form-horizontal');
		echo form_open_multipart('settings/update', $attributes); ?>
			 
				<input type="hidden" name="settings" value="<?=$load_setting?>">


				<div class="form-group">
						<label class="col-lg-5 control-label"><?=lang('automatic_activation')?></label>
						<div class="col-lg-3">
							<label class="switch">
								<input type="hidden" value="off" name="automatic_activation" />
								<input type="checkbox" <?php if(config_item('automatic_activation') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="automatic_activation">
								<span></span>
							</label>
						</div>
					</div>


				<div class="form-group">
						<label class="col-lg-5 control-label"><?=lang('suspend_due')?></label>
						<div class="col-lg-3">
							<label class="switch">
								<input type="hidden" value="off" name="suspend_due" />
								<input type="checkbox" <?php if(config_item('suspend_due') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="suspend_due">
								<span></span>
							</label>
						</div>
					</div>


					<div class="form-group">
						<label class="col-lg-5 control-label"><?=lang('terminate_due')?></label>
						<div class="col-lg-3">
							<label class="switch">
								<input type="hidden" value="off" name="terminate_due" />
								<input type="checkbox" <?php if(config_item('terminate_due') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="terminate_due">
								<span></span>
							</label>
						</div>
					</div>


					<div class="form-group">
						<label class="col-lg-5 control-label"><?=lang('apply_credit')?></label>
						<div class="col-lg-3">
							<label class="switch">
								<input type="hidden" value="off" name="apply_credit" />
								<input type="checkbox" <?php if(config_item('apply_credit') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="apply_credit">
								<span></span>
							</label>
						</div>
					</div>


					<div class="form-group">
						<label class="col-lg-5 control-label"><?=lang('notify_admin_payment_received')?></label>
						<div class="col-lg-3">
							<label class="switch">
								<input type="hidden" value="off" name="notify_payment_received" />
								<input type="checkbox" <?php if(config_item('notify_payment_received') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_payment_received">
								<span></span>
							</label>
						</div>
					</div>

					
					<div class="form-group">
						<label class="col-lg-5 control-label"><?=lang('display_invoice_badge')?></label>
						<div class="col-lg-3">
							<label class="switch">
								<input type="hidden" value="off" name="display_invoice_badge" />
								<input type="checkbox" <?php if(config_item('display_invoice_badge') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="display_invoice_badge">
								<span></span>
							</label>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-5 control-label"><?=lang('automatic_email_on_recur')?></label>
						<div class="col-lg-3">
							<label class="switch">
								<input type="hidden" value="off" name="automatic_email_on_recur" />
								<input type="checkbox" <?php if(config_item('automatic_email_on_recur') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="automatic_email_on_recur">
								<span></span>
							</label>
						</div>
					</div>


					<div class="form-group">
						<label class="col-lg-5 control-label">Order Tax</label>
						<div class="col-lg-3">
							<label class="switch">
								<input type="hidden" value="off" name="order_tax" />
								<input type="checkbox" <?php if(config_item('order_tax') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="order_tax">
								<span></span>
							</label>
						</div>
					</div>

			 
					<div class="form-group">
						<label class="col-lg-5 control-label"><?=lang('show_item_tax')?></label>
						<div class="col-lg-3">
							<label class="switch">
								<input type="hidden" value="off" name="show_invoice_tax" />
								<input type="checkbox" <?php if(config_item('show_invoice_tax') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="show_invoice_tax">
								<span></span>
							</label>
						</div>
					</div>
			
					<div class="form-group">
						<label class="col-lg-5 control-label"><?=lang('invoice_color')?> <span class="text-danger">*</span></label>
						<div class="col-lg-2">
							<input type="text" name="invoice_color" class="form-control" value="<?=config_item('invoice_color')?>" required>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-5 control-label"><?=lang('invoice_prefix')?> <span class="text-danger">*</span></label>
						<div class="col-lg-2">
							<input type="text" name="invoice_prefix" class="form-control" value="<?=config_item('invoice_prefix')?>" required>
						</div>
					</div>
			 
					<div class="form-group">
						<label class="col-lg-5 control-label"><?=lang('invoices_due_before')?></label>
						<div class="col-lg-2">
							<input type="text" name="invoices_due_before" class="form-control" value="<?=config_item('invoices_due_before')?>" required>
						</div>
						<div class="col-lg-2">
						<?=lang('days')?>
						</div>
					</div>


					<div class="form-group">
						<label class="col-lg-5 control-label"><?=lang('invoices_due_after')?> <span class="text-danger">*</span></label>
						<div class="col-lg-2">
							<input type="text" name="invoices_due_after" class="form-control" value="<?=config_item('invoices_due_after')?>" required>
						</div>
						<div class="col-lg-2">
						<?=lang('days')?>
						</div>
					</div>


					<div class="form-group">
						<label class="col-lg-5 control-label"><?=lang('suspend_after')?></label>
						<div class="col-lg-2">
							<input type="text" name="suspend_after" class="form-control" value="<?=config_item('suspend_after')?>" required>
						</div>
						<div class="col-lg-2">
						<?=lang('days')?>
						</div>
					</div>


					<div class="form-group">
						<label class="col-lg-5 control-label"><?=lang('terminate_after')?></label>
						<div class="col-lg-2">
							<input type="text" name="terminate_after" class="form-control"  value="<?=config_item('terminate_after')?>" required>
						</div>
						<div class="col-lg-2">
						<?=lang('days')?>
						</div>
					</div>					

					<div class="form-group">
						<label class="col-lg-5 control-label"><?=lang('invoice_start_number')?></label>
						<div class="col-lg-2">
							<input type="text" name="invoice_start_no" class="form-control" value="<?=config_item('invoice_start_no')?>">
						</div>
					</div>
					 			
		 
					<div class="form-group">
						<label class="col-lg-5 control-label"><?=lang('invoice_logo')?></label>
						<div class="col-lg-9">
							<div class="row">
								<div class="col-lg-22">
									<input type="file" class="filestyle" data-buttonText="<?=lang('choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="invoicelogo">
								</div>
							</div>
							<?php if (config_item('invoice_logo') != '') : ?>
								<div class="row">
									<div class="col-lg-6">
										<div id="invoice-logo-slider"></div>
									</div>
									<div class="col-lg-6">
										<div id="invoice-logo-dimensions"><?=config_item('invoice_logo_height')?>px x <?=config_item('invoice_logo_width')?>px</div>
									</div>
								</div>
								<input id="invoice-logo-height" type="hidden" value="<?=config_item('invoice_logo_height')?>" name="invoice_logo_height"/>
								<input id="invoice-logo-width" type="hidden" value="<?=config_item('invoice_logo_width')?>" name="invoice_logo_width"/>
								<div class="row h_150 mb_15">
									<div class="col-lg-22">
										<div class="invoice_image" style="height: <?=config_item('invoice_logo_height')?>px"><img src="<?=base_url()?>resource/images/logos/<?=config_item('invoice_logo')?>" /></div>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<div class="form-group terms">
						<label class="col-lg-5 control-label"><?=lang('default_terms')?></label>
						<div class="col-lg-9">
							<textarea class="form-control foeditor" name="default_terms"><?=config_item('default_terms')?></textarea>
						</div>
					</div>
					
					<div class="form-group terms">
						<label class="col-lg-5 control-label"><?=lang('invoice_footer')?></label>
						<div class="col-lg-9">
							<input type="text" class="form-control" name="invoice_footer" value="<?=config_item('invoice_footer')?>">
						</div>
					</div>
					
			 
					<div class="text-center">
						<button type="submit" class="btn btn-sm btn-<?=config_item('theme_color');?>"><?=lang('save_changes')?></button>
				</div> 
		</form>
 
	<!-- End Form -->
 