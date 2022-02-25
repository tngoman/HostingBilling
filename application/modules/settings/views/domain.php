    <!-- Start Form -->
    <?php
    $view = isset($_GET['view']) ? $_GET['view'] : '';
    $data['load_setting'] = $load_setting;
    switch ($view) {
        case 'currency':
            $this->load->view('currency',$data);
            break;
            default: ?>


        <?=$this->session->flashdata('form_error')?>
        <?php
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open('settings/update', $attributes); ?>
                 <input type="hidden" name="settings" value="<?=$load_setting?>">


                 <h4><?=lang('domain_admin_contact')?></h4>
          
                 <div class="box">  
                    
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('first_name')?></label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" value="<?=config_item('domain_admin_firstname')?>" name="domain_admin_firstname">
                            </div>
                        </div>

                        <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('last_name')?></label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" value="<?=config_item('domain_admin_lastname')?>" name="domain_admin_lastname">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('company_name')?></label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" value="<?=config_item('domain_admin_company')?>" name="domain_admin_company">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('address_line_1')?></label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" value="<?=config_item('domain_admin_address_1')?>" name="domain_admin_address_1">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('address_line_2')?></label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" value="<?=config_item('domain_admin_address_2')?>" name="domain_admin_address_2">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('city')?></label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" value="<?=config_item('domain_admin_city')?>" name="domain_admin_city">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('zip_code')?></label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" value="<?=config_item('domain_admin_zip')?>" name="domain_admin_zip">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('state_province')?></label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" value="<?=config_item('domain_admin_state')?>" name="domain_admin_state">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('country')?></label>
                        <div class="col-lg-4">
                            <select class="select2-option w_210" name="domain_admin_country" >
                                <optgroup label="<?=lang('selected_country')?>">
                                    <option value="<?=config_item('domain_admin_country')?>"><?=config_item('domain_admin_country')?></option>
                                </optgroup>
                                <optgroup label="<?=lang('other_countries')?>">
                                    <?php foreach ($countries as $country): ?>
                                        <option value="<?=$country->value?>"><?=$country->value?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('phone')?></label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" value="<?=config_item('domain_admin_phone')?>" name="domain_admin_phone">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('email')?></label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" value="<?=config_item('domain_admin_email')?>" name="domain_admin_email">
                        </div>
                    </div>

                    </div>
                 </div>


                 <h4><?=lang('defaultnameservers')?></h4>

                 <div class="box box-success">
            
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('nameserver_1')?></label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" value="<?=config_item('nameserver_one')?>" name="nameserver_one">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('nameserver_2')?></label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" value="<?=config_item('nameserver_two')?>" name="nameserver_two">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('nameserver_3')?></label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" value="<?=config_item('nameserver_three')?>" name="nameserver_three">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('nameserver_4')?></label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" value="<?=config_item('nameserver_four')?>" name="nameserver_four">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('nameserver_5')?></label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" value="<?=config_item('nameserver_five')?>" name="nameserver_five">
                            </div>
                        </div>


                    </div>
                 </div>
 
                   
                              
                    <div class="text-center">
                        <button type="submit" class="btn btn-sm btn-<?=config_item('theme_color');?>"><?=lang('save_changes')?></button>
                    </div>
                
        </form>

         <?php
            break;
    }
    ?>
 
    <!-- End Form -->
 