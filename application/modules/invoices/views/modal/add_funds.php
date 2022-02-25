<?php
  $user = User::get_id();            
  $user_company = User::profile_info($user)->company;
  $cur = Client::client_currency($user_company);
  ?>
<div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?=lang('add_funds')?></h4>
        </div><?php
            echo form_open(base_url().'invoices/add_funds_invoice'); ?>
                <div class="modal-body">
                   <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                        <input type="text" placeholder="0.00" name="amount" class="input-sm form-control" required>                                            
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                        <input type="text" name="currency" value="<?=config_item('default_currency')?>" class="input-sm form-control" readonly> 
                        </div>
                    </div>
                </div>      

                <hr>

                <div class="row">
                    <div class="col-md-12">
                            <div class="form-group">
                            <label><?=lang('create_invoice')?></label>                              
                                 <input type="checkbox" name="create_invoice">                            
                            </div>
                    </div> 
                </div>      

        </div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-<?=config_item('theme_color');?>"><?=lang('add_funds')?></button>
		</form>
	</div>
</div>
</div>
 