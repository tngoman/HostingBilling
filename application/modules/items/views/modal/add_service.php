<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('new_item')?></h4>
        </div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'items/add_service',$attributes); ?>
        <div class="modal-body">
            <input type="hidden" name="r_url" value="<?=base_url()?>items?view=service">
            <input type="hidden" name="quantity" value="1">
            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('category')?> <span class="text-danger">*</span></label>
                <div class="col-lg-5">
                    <select name="category" id="select2" class="form-control m-b" required>
                        <option value=""><?=lang('none')?></option>
                        <?php foreach ($categories as $key => $cat) { ?>
                        <option value="<?=$cat->id?>"><?=$cat->cat_name?></option>
                        <?php } ?>
                    </select>
                </div>
                <a href="<?=base_url()?>settings/add_category" class="btn btn-<?=config_item('theme_color');?> btn-sm"
                    data-toggle="ajaxModal" title="<?=lang('add_category')?>"><i class="fa fa-plus"></i>
                    <?=lang('add_category')?></a>
            </div>



            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('item_name')?> <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" placeholder="<?=lang('item_name')?>" name="item_name"
                        required>
                </div>
            </div>


            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('features')?> </label>
                <div class="col-lg-8">
                    <textarea class='form-control ta' name='item_features'
                        placeholder='<?=lang('features_example')?>'></textarea>
                </div>
            </div>


            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('setup_fee')?></label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" placeholder="0.00" name="setup_fee">
                </div>
            </div>


            <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('full_payment')?></label>
                <div class="col-lg-8">
                    <input type="text" id="price" class="form-control" placeholder="0.00" name="unit_cost">
                </div>
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
                        <label class="col-lg-7 control-label"><?=lang('require_domain')?></label>
                        <div class="col-lg-5">
                            <label class="switch">
                                <input type="hidden" value="off" name="require_domain" />
                                <input type="checkbox" name="require_domain">
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-lg-7 control-label"><?=lang('tax_rate')?></label>
                        <div class="col-lg-5">
                            <select name="item_tax_rate" class="form-control m-b">
                                <option value="0.00"><?=lang('none')?></option>
                                <?php foreach ($rates as $key => $tax) { ?>
                                <option value="<?=$tax->tax_rate_percent?>"><?=$tax->tax_rate_name?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-lg-7 control-label"><?=lang('display')?></label>
                        <div class="col-lg-5">
                            <label class="switch">
                                <input type="hidden" value="off" name="display" />
                                <input type="checkbox" name="display">
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>


            <div id="account_" role="tablist" aria-multiselectable="true">

                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="tOne">
                        <h5 class="panel-title">
                            <a data-toggle="collapse" data-parent="#account_" href="#tabOne" aria-expanded="false"
                                aria-controls="tabOne">
                                <?=lang('account_options')?>
                            </a>
                        </h5>
                    </div>
                    <div id="tabOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="tOne">
                        <div class="panel-body">


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-lg-7 control-label"><?=lang('create')?></label>
                                        <div class="col-lg-5">
                                            <label class="switch">
                                                <input type="hidden" value="off" name="create_account" />
                                                <input id="create_account" type="checkbox" name="create_account">
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <small class="pull-right danger"><?=lang('no_of_payments_help')?></small> <br />


                            <div class="row" id="installment_form">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-lg-8 control-label"><?=lang('monthly')?></label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" placeholder="0.00" name="monthly">
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-lg-8 control-label"><?=lang('no_of_payments')?></label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" name="monthly_payments">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-lg-8 control-label"><?=lang('quarterly')?></label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" placeholder="0.00" name="quarterly">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-lg-8 control-label"><?=lang('no_of_payments')?></label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" name="quarterly_payments">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-lg-8 control-label"><?=lang('semiannually')?></label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" placeholder="0.00"
                                                name="semi_annually">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-lg-8 control-label"><?=lang('no_of_payments')?></label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" name="semi_annually_payments">
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-lg-8 control-label"><?=lang('annually')?></label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" placeholder="0.00" name="annually">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-lg-8 control-label"><?=lang('no_of_payments')?></label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" name="annually_payments">
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-lg-8 control-label"><?=lang('biennially')?></label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control" placeholder="0.00" name="biennially">
                                    </div>
                                </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-lg-8 control-label"><?=lang('no_of_payments')?></label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" name="biennially_payments">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-lg-8 control-label"><?=lang('triennially')?></label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" placeholder="0.00" name="triennially">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-lg-8 control-label"><?=lang('no_of_payments')?></label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" name="triennially_payments">
                                        </div>
                                    </div>
                                </div>
                            </div>

 

                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="col-lg-8 control-label"><?=lang('reseller_package')?></label>
                                        <div class="col-lg-4">
                                            <label class="switch">
                                                <input type="hidden" value="off" name="reseller_package" />
                                                <input type="checkbox" name="reseller_package">
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-4"></div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="col-lg-8 control-label"><?=lang('allow_upgrade')?></label>
                                        <div class="col-lg-4">
                                            <label class="switch">
                                                <input type="hidden" value="off" name="allow_upgrade" />
                                                <input type="checkbox" name="allow_upgrade">
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row"> 
                                <div class="form-group">
                                    <label class="col-lg-9 control-label"><?=lang('price_change')?></label>
                                    <div class="col-lg-3">&nbsp;
                                    <label class="switch">
                                            <input type="hidden" value="off" name="price_change" />
                                            <input type="checkbox" name="price_change"> 
                                                <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer"> <a href="#" class="btn btn-default"
                        data-dismiss="modal"><?=lang('close')?></a>
                    <button type="submit"
                        class="btn btn-<?=config_item('theme_color');?>"><?=lang('add_item')?></button>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->