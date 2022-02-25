<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title">Paying <strong><?=$invoice_info['currency']?> <?=number_format($invoice_info['amount'],2)?></strong> for Invoice #<?=$invoice_info['item_name']?> via Bitcoin</h4>
		</div>		
		<div class="modal-body">
<?php
		$attributes = array('id'=>'payment-form','class' => 'bs-example form-horizontal');
		if (isset($errors) && !empty($errors) && is_array($errors)) {
			echo '<div class="alert alert-error"><h4>Error!</h4>The following error(s) occurred:<ul>';
			foreach ($errors as $e) {
				echo "<li>$e</li>";
			}
			echo '</ul></div>';	
		}?>
		
		<div id="payment-errors"></div>
		<input type="hidden" name="invoice_id" value="<?=$invoice_info['item_number']?>">
		<input type="hidden" name="amount" value="<?=number_format($invoice_info['amount'],2)?>">
		<input type="hidden" name="btc_amount" value="<?=$btc_amount ?>">

		
		<h4>Send <?=$btc_amount?> BTC to <a><?=$btc_address?></a> or scan address below.</h4>		 
		<div id="code" style="max-width: 128px; margin: 20px auto;"></div>	 
		 <script src="<?=base_url()?>resource/js/qrcode.min.js"></script>
		 <script>
		 var qrcode = new QRCode("code", {
			text: '<?=$btc_address?>',
			width: 128,
			height: 128,
			colorDark : "#000000",
			colorLight : "#ffffff",
			correctLevel : QRCode.CorrectLevel.H
		});
		</script>
		<br>
		<div class="alert alert-info" class="center">Your invoice will be marked as paid automatically.</div>
				<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		</div>
				
			
		</div>
		
		</form>





	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->