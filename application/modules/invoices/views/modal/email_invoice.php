<div class="modal-dialog">
	<div class="modal-content">
	<?php $invoice = Invoice::view_by_id($id); ?>
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('email_invoice')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'invoices/send_invoice',$attributes); ?>
		<div class="modal-body">
			<input type="hidden" name="invoice" value="<?=$invoice->inv_id?>">

          	<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('subject')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?php echo App::email_template('invoice_message','subject');?> <?=$invoice->reference_no?>" name="subject">
				</div>
			</div>



				<input type="hidden" name="message" class="hiddenmessage">

				<div class="message" contenteditable="true">
				<?php echo App::email_template('invoice_message','template_body');?>
									</div>

				<div class="form-group">
                        <label class="col-lg-7 control-label"><?=lang('cc_self')?> ( <span class="it"><?=User::login_info(User::get_id())->email?></span> )</label>
                        <div class="col-lg-5">
                            <label class="switch">
                                <input type="checkbox" name="cc_self">
                                <span></span>
                            </label>
                        </div>
                </div>

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="submit btn btn-<?=config_item('theme_color');?>"><?=lang('email_invoice')?></button>
		</form>
		</div>
	</div>	<!-- /.modal-content -->


<script type="text/javascript">
	(function($){
    "use strict";	
    $('.submit').on('click', function () {
        var mysave = $('.message').html();
        $('.hiddenmessage').val(mysave);
    });
})(jQuery);
</script>



</div>
<!-- /.modal-dialog -->
