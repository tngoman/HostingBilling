<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('new_item')?></h4>
        </div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'items/add_item',$attributes); ?>
        <div class="modal-body">
            <input type="hidden" name="r_url" value="<?=base_url()?>items?view=domains">
            <input type="hidden" name="quantity" value="1">
            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('category')?> <span class="text-danger">*</span></label>
                <div class="col-lg-5">
                    <select name="category" class="form-control m-b" required>
                        <?php foreach ($categories as $key => $cat) { if($cat->parent == 8) { ?>
                        <option value="<?=$cat->id?>"><?=$cat->cat_name?></option>
                        <?php }} ?>
                    </select>
                </div>
                <a href="<?=base_url()?>settings/add_category" class="btn btn-<?=config_item('theme_color');?> btn-sm"
                    data-toggle="ajaxModal" title="<?=lang('add_category')?>"><i class="fa fa-plus"></i>
                    <?=lang('add_category')?></a>
            </div>

            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('extension')?> <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" placeholder="com" name="item_name" required>
                </div>
            </div>


            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('registrar')?></label>
                <div class="col-lg-8">
                    <select class="form-control" name="default_registrar">
                        <option value=""><?=lang('none')?></option>
                        <?php $registrars = Plugin::domain_registrars();
                        foreach ($registrars as $registrar) {?>
                        <option value="<?=$registrar->system_name;?>"><?=ucfirst($registrar->system_name);?></option><?php } ?>
                    </select>
                </div>
            </div>


            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('registration')?></label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" placeholder="0.00" name="registration">
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('transfer')?></label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" placeholder="0.00" name="transfer">
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('renewal')?></label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" placeholder="0.00" name="renewal">
                </div>
            </div>


			<div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('max_years')?></label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" value="1" name="max_years">
                </div>
            </div>


		<div class="form-group">
			<label class="col-lg-4 control-label"><?=lang('tax_rate')?></label>
			<div class="col-lg-8">
				<select name="item_tax_rate" class="form-control m-b">
					<option value="0.00"><?=lang('none')?></option>
					<?php foreach ($rates as $key => $tax) { ?>
					<option value="<?=$tax->tax_rate_percent?>"><?=$tax->tax_rate_name?></option>
					<?php } ?>
				</select>
			</div>
           


            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-lg-8 control-label"><?=lang('order')?></label>
                        <div class="col-lg-4">
                            <input type="text" id="order_by" class="form-control" placeholder="1" name="order_by">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('display')?></label>
                        <div class="col-lg-8">
                            <label class="switch">
                                <input type="hidden" value="off" name="display" />
                                <input type="checkbox" name="display">
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>




        </div>
        <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
            <button type="submit" class="btn btn-<?=config_item('theme_color');?>"><?=lang('add_item')?></button>
            </form>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->