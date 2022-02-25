
<div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
        <?php $withdrawal = Affiliate::withdrawal($id);
        if(!empty($withdrawal)){
        ?>
        <h4 class="modal-title"><?=$withdrawal->company_name?></h4>
        </div>
        <?php
            echo form_open(base_url().'affiliates/pay_withdrawal'); ?>
            <input type="hidden" name="id" value="<?=$id?>">
            <input type="hidden" name="withdrawal_id" value="<?=$withdrawal->withdrawal_id?>">
            <div class="modal-body">

            <?=$withdrawal->payment_details?>
            <hr>
                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label"><?=lang('amount')?></label>
                        <input type="text" value="<?=$withdrawal->amount?>" name="amount" class="input-sm form-control" readonly="readonly">                                            
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
            <label class="control-label"><?=lang('notes')?></label>
            <textarea class="form-control" name="notes"></textarea>
            </div>

            <?php } else { ?>
                <h4 class="modal-title"><?=lang('no_withdrawal_request')?></h4>
            <?php } ?>
            
        </div>
		<div class="modal-footer"> <a href="#" class="btn btn-default btn-sm" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-success btn-sm"><?=lang('pay_withdrawal')?></button>
		</form>
	</div>
</div>
</div>
 