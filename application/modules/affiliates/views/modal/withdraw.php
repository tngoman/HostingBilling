<?php
  $user = User::get_id();            
  $user_company = User::profile_info($user)->company;
  $cur = Client::client_currency($user_company);
  ?>
<div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?=lang('request_withdrawal')?></h4>
        </div>
        <?php
            echo form_open(base_url().'affiliates/submit_withdrawal'); ?>
            <div class="modal-body">
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label"><?=lang('amount')?></label>
                        <input type="text" placeholder="0.00" name="amount" class="input-sm form-control" required>                                            
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                    <label class="control-label"><?=lang('currency')?></label>
                    <input type="text" name="currency" value="<?=config_item('default_currency')?>" class="input-sm form-control" readonly> 
                    </div>
                </div>
            </div>  
            
            <div class="form-group">
            <label class="control-label"><?=lang('payment_details')?></label>
            <textarea class="form-control" name="payment_details"></textarea>
            </div>

        </div>
		<div class="modal-footer"> <a href="#" class="btn btn-default btn-sm" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-success btn-sm"><?=lang('submit_withdrawal_request')?></button>
		</form>
	</div>
</div>
</div>
 