<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('addon')?></h4>

            <?php $item = Item::view_item($id); ?>

        </div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'items/edit_addon/' . $id ,$attributes); ?>
        <div class="modal-body">
            <input type="hidden" name="quantity" value="1">
            <input type="hidden" name="r_url" value="addons">
            <input type="hidden" name="addon" value="1">
            <input type="hidden" name="item_id" value="<?=$id?>">

            <div class="form-group">
                <label class="col-lg-3 control-label"><?=lang('item_name')?> <span class="text-danger">*</span></label>
                <div class="col-lg-9">
                    <input type="text" class="form-control" value="<?=$item->item_name?>" name="item_name" required>
                </div>
            </div>


            <div class="form-group">
                <label class="col-lg-3 control-label"><?=lang('description')?> </label>
                <div class="col-lg-9">
                    <textarea rows="2" cols="2" class='form-control ta' name='item_desc'
                        value='<?=$item->item_desc?>'><?=$item->item_desc?></textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-3 control-label"><?=lang('add_to')?></label>
                <div class="col-lg-9">
                    <select class="select2" multiple="multiple" style="width: 100%;" name="apply_to[]">
                        <?php foreach(Item::get_items() as $i) { ?>
                        <option value="<?=$i->item_id?>"><?=$i->item_name?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>


            <div class="row">
                <div class="col-md-6">

                    <div class="form-group">
                        <label class="col-lg-6 control-label"><?=lang('order')?></label>
                        <div class="col-lg-5">
                            <input type="text" id="order_by" class="form-control" value="<?=$item->order_by?>"
                                name="order_by">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-lg-6 control-label"><?=lang('allow_upgrade')?></label>
                        <div class="col-lg-6">
                            <label class="switch">
                                <input type="hidden" value="off" name="allow_upgrade" />
                                <input type="checkbox"
                                    <?php if($item->allow_upgrade == 'Yes'){ echo "checked=\"checked\""; } ?>
                                    name="allow_upgrade">
                                <span></span>
                            </label>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-lg-6 control-label"><?=lang('display')?></label>
                        <div class="col-lg-6">
                            <label class="switch">
                                <input type="hidden" value="off" name="display" />
                                <input type="checkbox"
                                    <?php if($item->display == 'Yes'){ echo "checked=\"checked\""; } ?> name="display">
                                <span></span>
                            </label>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-lg-6 control-label"><?=lang('tax_rate')?></label>
                        <div class="col-lg-6">
                            <select name="item_tax_rate" class="form-control m-b">
                                <option value="<?=$item->item_tax_rate?>"><?=$item->item_tax_rate?></option>
                                <option value="0.00"><?=lang('none')?></option>
                                <?php foreach ($rates as $key => $tax) { ?>
                                <option value="<?=$tax->tax_rate_percent?>"><?=$tax->tax_rate_name?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-lg-6 control-label"><?=lang('setup_fee')?></label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" value="<?=$item->setup_fee?>" name="setup_fee">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-lg-6 control-label"><?=lang('full_payment')?></label>
                        <div class="col-lg-6">
                            <input type="text" id="price" class="form-control" value="<?=$item->unit_cost?>"
                                name="unit_cost">
                        </div>
                    </div>


                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-lg-6 control-label"><?=lang('monthly')?></label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" value="<?=$item->monthly?>" name="monthly">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-6 control-label"><?=lang('quarterly')?></label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" value="<?=$item->quarterly?>" name="quarterly">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-6 control-label"><?=lang('semiannually')?></label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" value="<?=$item->semi_annually?>"
                                name="semi_annually">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-6 control-label"><?=lang('annually')?></label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" value="<?=$item->annually?>" name="annually">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-6 control-label"><?=lang('biennially')?></label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" value="<?=$item->biennially?>" name="biennially">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-lg-6 control-label"><?=lang('triennially')?></label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" value="<?=$item->triennially?>" name="triennially">
                        </div>
                    </div>
                </div>

            </div>





        </div>
        <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
            <button type="submit" class="btn btn-<?=config_item('theme_color');?>"><?=lang('save')?></button>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->


<script type="text/javascript">
$('.select2').select2();
var apply_to = JSON.parse('<?= addslashes(json_encode(unserialize($item->apply_to))) ?>');
$('.select2').val(apply_to).trigger('change');
</script>