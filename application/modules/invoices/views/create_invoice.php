                   <!-- Start create invoice -->
                    <div class="box"> 
                            <div class="box-body">

                                <?php
                                $attributes = array('class' => 'bs-example form-horizontal');
                                echo form_open(base_url().'invoices/add',$attributes);
                                ?>
                                <?php echo validation_errors(); ?>
                                


                                <div class="row">
                                <div class="col-md-6">

                                <div class="form-group">
                                            <label class="col-lg-3 control-label"><?=lang('client')?> <span class="text-danger">*</span> </label>
                                            <div class="col-lg-6">
                                                <select class="select2-option w_280" name="client" >
                                                    <optgroup label="<?=lang('clients')?>">
                                                        <?php foreach (Client::get_all_clients() as $client): ?>
                                                            <option value="<?=$client->co_id?>"><?=ucfirst($client->company_name)?></option>
                                                        <?php endforeach;  ?>
                                                    </optgroup>
                                                </select>
                                            </div>
                                            <a href="<?=base_url()?>companies/create" class="btn btn-<?=config_item('theme_color');?> btn-sm" data-toggle="ajaxModal" title="<?=lang('new_company')?>" data-placement="bottom"><i class="fa fa-plus"></i> <?=lang('new_client')?></a>
                                        </div>
 

                                <div class="form-group">
                                            <label class="col-lg-3 control-label"><?=lang('tax')?> </label>
                                            <div class="col-lg-8">
                                                <div class="input-group">
                                                    <span class="input-group-addon">%</span>
                                                    <input class="form-control money" type="text" value="<?=config_item('default_tax')?>" name="tax">
                                                </div>
                                            </div>
                                        </div>
                                
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?=lang('discount')?></label>
                                            <div class="col-lg-8">
                                                <div class="input-group">
                                                    <span class="input-group-addon">%</span>
                                                    <input class="form-control money" type="text" value="<?=set_value('discount')?>" name="discount">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?=lang('extra_fee')?></label>
                                            <div class="col-lg-8">
                                                <div class="input-group">
                                                    <span class="input-group-addon">%</span>
                                                    <input class="form-control money" type="text" value="<?=set_value('extra_fee')?>" name="extra_fee">
                                                </div>
                                            </div>
                                        </div>

                                       
                                </div>

                                <div class="col-md-6">

                                        <div class="form-group">
                                            <label class="col-lg-4 control-label"><?=lang('reference_no')?> <span class="text-danger">*</span></label>
                                            <div class="col-lg-5">
                                                <input type="text" class="form-control" value="<?=config_item('invoice_prefix')?><?php
                                                if(config_item('increment_invoice_number') == 'FALSE'){
                                                    $this->load->helper('string');
                                                    echo random_string('nozero', 6);
                                                }else{
                                                    echo Invoice::generate_invoice_number();
                                                }
                                                ?>" name="reference_no">
                                            </div>

                                        </div>


                                        <div class="form-group">
                                            <label class="col-lg-4 control-label"><?=lang('due_date')?></label>
                                            <div class="col-lg-5">
                                                <input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="<?=strftime(config_item('date_format'), strtotime("+".config_item('invoices_due_after')." days"));?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
                                            </div>
                                        </div>

 
                                </div>
                                </div>

                                <div class="row">

                                
                                    <div class="col-lg-1"> </div>
                                        <div class="col-lg-9">
                                            <div class="form-group terms">
                                                <label class="control-label"><?=lang('notes')?> </label>
                                                <textarea name="notes" class="form-control foeditor" value="notes"><?=config_item('default_terms')?></textarea>
                                            </div>
                                        </div>
                                    </div>




                                <button type="submit" class="btn btn-sm btn-<?=config_item('theme_color');?> pull-right"><i class="fa fa-plus"></i> <?=lang('create_invoice')?></button>



                                </form>
                            </div> 
                    </div> 