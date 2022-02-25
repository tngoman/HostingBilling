<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('edit_item')?> - <?=lang('affiliates')?></h4>
        </div>
        <?php $item = Item::view_item($id); ?>

        <?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'items/affiliates',$attributes); ?>
        <input type="hidden" name="r_url" value="<?=base_url()?>items?view=hosting">
        <input type="hidden" name="item_id" value="<?=$item->item_id?>">
        <div class="modal-body">

            <div class="form-group">
                <label class="col-lg-4 control-label">Commission</label>
                <div class="col-lg-3">
                    <select name="commission" class="form-control m-b" id="type"> 
                        <option value="default" <?php echo ($item->commission == "default") ? 'selected' : ''; ?>>Default</option>
                        <option value="amount" <?php echo ($item->commission == "amount") ? 'selected' : ''; ?>>Amount</option>
                        <option value="percentage" <?php echo ($item->commission == "percentage") ? 'selected' : ''; ?>>Percentage</option>
                        <option value="none" <?php echo ($item->commission == "none") ? 'selected' : ''; ?>>None</option>
                    </select>
                </div>               
            </div>

            <div class="form-group" id="amount">
                <label class="col-lg-4 control-label">Commission Amount</label>
                <div class="col-lg-3">                    
                    <div class="input-group">                        
                        <input type="text" class="form-control" value="<?=$item->commission_amount?>" name="commission_amount">
                        <span class="input-group-addon">%</span>                        
                    </div>                    
                </div>               
            </div>

            <div class="form-group">
                <label class="col-lg-4 control-label">Payout</label>
                <div class="col-lg-3">
                    <select name="commission_payout" class="form-control m-b"> 
                        <option value="default" <?php echo ($item->commission_payout == "default") ? 'selected' : ''; ?>>Default</option>
                        <option value="once" <?php echo ($item->commission_payout == "once") ? 'selected' : ''; ?>>Once Off</option>
                        <option value="recurring" <?php echo ($item->commission_payout== "recurring") ? 'selected' : ''; ?>>Recurring</option> 
                    </select>
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
<script>
    <?php if($item->commission != 'percentage' && $item->commission != 'amount') { ?>
        $('#amount').hide();
    <?php } ?>

    $('#type').on('change', function() {
        var selected = $(this).find('option:selected').val();
        if(selected == 'percentage' || selected == 'amount')
        {
            $('#amount').show(500);
        }
        else
        {
            $('#amount').hide(500);
        }

        if(selected == 'percentage')
        {
            $('.input-group-addon').show(500);
        }
        else
        {
            $('.input-group-addon').hide(500);
        }
    });
    
    $(this).showCategoryFields($('#item_category')[0]);
</script>