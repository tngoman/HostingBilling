<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('new_payment')?></h4>
		</div>		
		<div class="modal-body">
			<p><?=lang('paypal_redirection_alert')?></p>

		<?php
			$attributes = array('name'=>'paypal_form','class' => 'bs-example form-horizontal');
                        echo form_open($paypal_url,$attributes);
                        $cur = App::currencies($info['currency']);
                ?>
					<input name="rm" value="2" type="hidden">
					<input name="cmd" value="_xclick" type="hidden">
					<input name="currency_code" value="<?=$info['currency']?>" type="hidden">
					<input name="quantity" value="1" type="hidden">
					<input name="business" value="<?=$email?>" type="hidden">
					<input name="return" value="<?=base_url()?>invoices/view/<?=$info['item_number']?>" type="hidden">
					<input name="cancel_return" value="<?=base_url()?>paypal/cancel" type="hidden">
					<input name="notify_url" value="<?=base_url()?>paypal/ipn" type="hidden">
					<input name="custom" value="<?=$info['client'];?>" type="hidden">
					<input name="item_name" value="<?=$info['item_name']?>" type="hidden">
					<input name="item_number" value="<?=$info['item_number']?>" type="hidden">
			 <div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('reference_no')?></label>
				<div class="col-lg-4">
					<input type="text" class="form-control" readonly value="<?=$info['item_name']?>">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('due_amount')?> (<?=$cur->symbol?>) </label>
				<div class="col-lg-4">
					<input type="text" class="form-control" value="<?=number_format($info['amount'],2)?>"  name="amount" readonly>
				</div>
				</div>
 
				<img src="<?=base_url()?>resource/images/payment_american.png">
				<img src="<?=base_url()?>resource/images/payment_discover.png">
				<img src="<?=base_url()?>resource/images/payment_maestro.png">
				<img src="<?=base_url()?>resource/images/payment_paypal.png">

				<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-success"><?=lang('pay_invoice')?></button>
		</div>
				
			
		</div>
		
		</form>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->