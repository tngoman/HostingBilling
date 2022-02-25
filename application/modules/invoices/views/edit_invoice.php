            <div class="box">
                <div class="box-header b-b clearfix">               
                    <?php $i = Invoice::view_by_id($id); ?>
                    <strong><i class="fa fa-info-circle"></i> <?=lang('invoice_details')?> - <?=$i->reference_no?></strong>                 
                        <a href="<?=base_url()?>invoices/view/<?=$i->inv_id?>" data-original-title="<?=lang('view_items')?>" data-toggle="tooltip" data-placement="bottom" class="btn btn-<?=config_item('theme_color');?> btn-sm pull-right"><i class="fa fa-info-circle"></i> <?=lang('invoice_items')?></a>
                 </div> 

                    <div class="box-body"> 
                                <?php
                                $attributes = array('class' => 'bs-example form-horizontal');
                                echo form_open(base_url().'invoices/edit',$attributes); ?>
                                <input type="hidden" name="inv_id" value="<?=$i->inv_id?>">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?=lang('reference_no')?> <span class="text-danger">*</span></label>
                                            <div class="col-lg-4">
                                                <input type="text" class="form-control" value="<?=$i->reference_no?>" name="reference_no">
                                            </div>
                                            <a href="#recurring" class="btn btn-xs btn-<?=config_item('theme_color');?>" data-toggle="class:show"><?=lang('recurring')?></a>
                                        </div>
                                        <!-- Start discount fields -->
                                        <div id="recurring" class="hide">
                                            <div class="form-group">
                                                <label class="col-lg-3 control-label"><?=lang('recur_frequency')?> </label>
                                                <div class="col-lg-4">
                                                    <select name="r_freq" class="form-control">
                                                        <option value="none"><?=lang('none')?></option>
                                                        <option value="7D"<?=($i->recur_frequency == "7D" ? ' selected="selected"' : '')?>><?=lang('week')?></option>
                                                        <option value="1M"<?=($i->recur_frequency == "1M" ? ' selected="selected"' : '')?>><?=lang('month')?></option>
                                                        <option value="3M"<?=($i->recur_frequency == "3M" ? ' selected="selected"' : '')?>><?=lang('quarter')?></option>
                                                        <option value="6M"<?=($i->recur_frequency == "6M" ? ' selected="selected"' : '')?>><?=lang('six_months')?></option>
                                                        <option value="1Y"<?=($i->recur_frequency == "1Y" ? ' selected="selected"' : '')?>><?=lang('year')?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-3 control-label"><?=lang('start_date')?></label>
                                                <div class="col-lg-8">
                                                    <?php if ($i->recurring == 'Yes') {
                                                        $recur_start_date = date('d-m-Y',strtotime($i->recur_start_date));
                                                        $recur_end_date = date('d-m-Y',strtotime($i->recur_end_date));
                                                    }else{
                                                        $recur_start_date = date('d-m-Y');
                                                        $recur_end_date = date('d-m-Y');
                                                    }
                                                    ?>
                                                    <input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), strtotime($recur_start_date));?>" name="recur_start_date" data-date-format="<?=config_item('date_picker_format');?>" >
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-3 control-label"><?=lang('end_date')?></label>
                                                <div class="col-lg-8">
                                                    <input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), strtotime($recur_end_date));?>" name="recur_end_date" data-date-format="<?=config_item('date_picker_format');?>" >
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End discount Fields -->
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?=lang('client')?> <span class="text-danger">*</span> </label>
                                            <div class="col-lg-6">
                                                <select class="select2-option w_280" name="client" >
                                                    <optgroup label="<?=lang('clients')?>">
                                                        <option value="<?=$i->client?>">
                                                            <?=ucfirst(Client::view_by_id($i->client)->company_name)?></option>
                                                        <?php foreach ($clients as $client): ?>
                                                            <option value="<?=$client->co_id?>">
                                                                <?=ucfirst($client->company_name)?></option>
                                                        <?php endforeach;  ?>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?=lang('currency')?></label>
                                            <div class="col-lg-8">
                                                <select name="currency" class="form-control">
                                                    <?php $cur = App::currencies($i->currency); ?>
                                                    <?php foreach ($currencies as $cur) : ?>
                                                        <option value="<?=$cur->code?>"<?=($i->currency == $cur->code ? ' selected="selected"' : '')?>><?=$cur->name?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?=lang('due_date')?></label>
                                            <div class="col-lg-8">
                                                <input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), strtotime($i->due_date));?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?=lang('created')?></label>
                                            <div class="col-lg-8">
                                                <input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), strtotime($i->date_saved));?>" name="date_saved" data-date-format="<?=config_item('date_picker_format');?>" >
                                            </div>
                                        </div>
                               </div>
                                <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label"><?=lang('tax')?></label>
                                            <div class="col-lg-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">%</span>
                                                    <input class="form-control money" type="text" value="<?=$i->tax?>" name="tax">
                                                </div>
                                            </div>
                                        </div>
        
                                        <!-- Start discount fields -->
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label"><?=lang('discount')?> </label>
                                            <div class="col-lg-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">%</span>
                                                    <input class="form-control money" type="text" value="<?=$i->discount?>" name="discount">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End discount Fields -->

                                        <div class="form-group">
                                            <label class="col-lg-4 control-label"><?=lang('extra_fee')?></label>
                                            <div class="col-lg-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">%</span>
                                                    <input class="form-control money" type="text" value="<?=$i->extra_fee?>" name="extra_fee">
                                                </div>
                                            </div>
                                        </div>

 
                                    </div>
                               </div>

                                <div class="form-group">
                                    <label class="col-lg-1 control-label"><?=lang('notes')?> </label>
                                    <div class="col-lg-8">
                                        <textarea name="notes" class="form-control foeditor"><?=$i->notes?></textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-sm btn-<?=config_item('theme_color');?> pull-right"> <?=lang('save_changes')?></button>
                                </form>
                            </div> 
                    </div>
             