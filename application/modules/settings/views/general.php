    <!-- Start Form --> 
        <?php
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open_multipart('settings/update', $attributes); ?>
    
    <input type="hidden" name="settings" value="<?=$load_setting?>">
                    <input type="hidden" name="languages" value="<?=implode(",",$translations)?>">
              
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=lang('website_name')?> <span class="text-danger">*</span></label>
                                <div class="col-lg-5">
                                    <input type="text" name="website_name" class="form-control" value="<?=config_item('website_name')?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=lang('site_desc')?> <span class="text-danger">*</span></label>
                                <div class="col-lg-5">
                                    <textarea type="text" name="site_desc" class="form-control" value="<?=config_item('site_desc')?>" required><?=config_item('site_desc')?></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=lang('company_name')?> <span class="text-danger">*</span></label>
                                <div class="col-lg-5">
                                    <input type="text" name="company_name" class="form-control" value="<?=config_item('company_name')?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=lang('company_legal_name')?> <span class="text-danger">*</span></label>
                                <div class="col-lg-5">
                                    <input type="text" name="company_legal_name" class="form-control" value="<?=config_item('company_legal_name')?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=lang('contact_person')?> </label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control"  value="<?=config_item('contact_person')?>" name="contact_person">
                                    <span class="help-block m-b-none"><?=lang('company_representative')?></strong>.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=lang('company_address')?> <span class="text-danger">*</span></label>
                                <div class="col-lg-5">
                                    <textarea class="form-control ta" name="company_address" required><?=config_item('company_address')?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=lang('zip_code')?> </label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control"  value="<?=config_item('company_zip_code')?>" name="company_zip_code">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=lang('city')?></label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control" value="<?=config_item('company_city')?>" name="company_city">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=lang('state_province')?></label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control" value="<?=config_item('company_state')?>" name="company_state">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=lang('country')?></label>
                                <div class="col-lg-5">
                                        <select class="select2-option w_210" name="company_country" >
                                            <optgroup label="<?=lang('selected_country')?>">
                                                <option value="<?=config_item('company_country')?>"><?=config_item('company_country')?></option>
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
                                <label class="col-lg-3 control-label"><?=lang('company_email')?></label>
                                <div class="col-lg-5">
                                    <input type="email" class="form-control" value="<?=config_item('company_email')?>" name="company_email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=lang('company_phone')?></label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control" value="<?=config_item('company_phone')?>" name="company_phone">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=lang('company_phone')?> 2</label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control" value="<?=config_item('company_phone_2')?>" name="company_phone_2">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=lang('mobile_phone')?></label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control" value="<?=config_item('company_mobile')?>" name="company_mobile">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=lang('fax')?> </label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control" value="<?=config_item('company_fax')?>" name="company_fax">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=lang('company_domain')?></label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control" value="<?=config_item('company_domain')?>" name="company_domain">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=lang('company_registration')?></label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control" value="<?=config_item('company_registration')?>" name="company_registration">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=lang('company_vat')?></label>
                                <div class="col-lg-5">
                                    <input type="text" class="form-control" value="<?=config_item('company_vat')?>" name="company_vat">
                                </div>
                            </div>
                            
         
                            <div class="text-center"> 
                            <button type="submit" class="btn btn-sm btn-<?=config_item('theme_color');?> pull-right"><?=lang('save_changes')?></button>
                         </div>
        </form> 
    <!-- End Form -->
 