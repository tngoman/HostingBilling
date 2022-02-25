<?php $company_ref = config_item('company_id_prefix').$this->applib->generate_string();
while($this->db->where('company_ref', $company_ref)->get('companies')->num_rows() == 1) {
$company_ref = config_item('company_id_prefix').$this->applib->generate_string();
} ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?=lang('new_client')?></h4>
        </div><?php
            echo form_open(base_url().'companies/create'); ?>
        <div class="modal-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li><a class="active" data-toggle="tab" href="#tab-client-general"><?=lang('details')?></a></li>
                        <li><a data-toggle="tab" href="#tab-client-contact"><?=lang('address')?></a></li>
                        <li><a data-toggle="tab" href="#tab-client-custom"><?=lang('custom_fields')?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab-client-general">

			 <input type="hidden" name="company_ref" value="<?=$company_ref?>">
                         <div class="row">
                                <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?=lang('company_name')?> / <?=lang('full_name')?><span class="text-danger">*</span></label>
                                            <input type="text" name="company_name" value="" class="input-sm form-control" required>
                                        </div>

                                        <div class="form-group">
                                                <label><?=lang('email')?> <span class="text-danger">*</span></label>
                                                <input type="email" name="company_email" value="" class="input-sm form-control" required>
                                        </div>

                                        <div class="form-group">
                                                <label><?=lang('username')?> <span class="text-danger">*</span></label>
                                                <input type="text" name="username" value="" class="input-sm form-control" required>
                                        </div>

                                        <div class="form-group">
                                                <label><?=lang('password')?> </label>
                                                <input type="password" value="" name="password"  class="input-sm form-control">
                                        </div>


                                        <div class="form-group">
                                                <label><?=lang('confirm_password')?> </label>
                                                <input type="password" value="" name="confirm_password"  class="input-sm form-control">
                                        </div>
                               
                                        
                                </div>

                                <div class="col-md-6">
                                        <div class="form-group">
                                                <label><?=lang('vat')?> <?=lang('number')?> </label>
                                                <input type="text" value="" name="VAT" class="input-sm form-control">
                                        </div>

                                        <div class="form-group">
                                                <label><?=lang('mobile_phone')?> </label>
                                                <input type="text" value="" name="company_mobile"  class="input-sm form-control">
                                        </div>
                                         

                                        <div class="form-group">
                                            <label><?=lang('phone')?> </label>
                                            <input type="text" value="" name="company_phone"  class="input-sm form-control">
                                        </div>

                                        <div class="form-group">
                                            <label><?=lang('fax')?> </label>
                                            <input type="text" value="" name="company_fax"  class="input-sm form-control">
                                        </div>

                                        <div class="form-group">
                                        <label><?=lang('currency')?></label>
                                        <select name="currency" class="form-control">
                                        <?php foreach (App::currencies() as $cur) : ?>
                                        <option value="<?=$cur->code?>"<?=(config_item('default_currency') == $cur->code ? ' selected="selected"' : '')?>><?=$cur->name?></option>
                                        <?php endforeach; ?>
                                        </select>
                                     </div>

                                </div>
                         </div>                                                      
                            

                            <div class="form-group">
                                <label><?=lang('notes')?></label>
                                <textarea name="notes" class="form-control ta" placeholder="<?=lang('notes')?>" ></textarea>
                            </div>

                        </div>
                        <div class="tab-pane fade in" id="tab-client-contact">
                                
                             
                        
                                <div class="form-group">
                                        <label><?=lang('address')?></label>
                                        <textarea name="company_address" class="form-control"></textarea>
                                </div>
                                <div class="form-group col-md-6 no-gutter-left">
                                        <label><?=lang('city')?> </label>
                                        <input type="text" value="" name="city" class="input-sm form-control">
                                </div>
                                <div class="form-group col-md-6 no-gutter-right">
                                        <label><?=lang('zip_code')?> </label>
                                        <input type="text" value="" name="zip" class="input-sm form-control">
                                </div>
                            <div class="row">
                            <div class="form-group col-md-6">
                                    <label><?=lang('state_province')?> </label>
                                    <input type="text" value="" name="state" class="input-sm form-control">
                            </div>
                                <div class="form-group col-md-6">

                     
                                        <label><?=lang('language')?></label>
                                        <select name="language" class="form-control">
                                        <?php foreach (App::languages() as $lang) : ?>
                                        <option value="<?=$lang->name?>"<?=(config_item('default_language') == $lang->name ? ' selected="selected"' : '')?>><?=  ucfirst($lang->name)?></option>
                                        <?php endforeach; ?>
                                        </select>

                                                               
                                        <label><?=lang('country')?> </label>
                                        <select class="form-control w_180" name="country" >
                                                <optgroup label="<?=lang('selected_country')?>">
                                                        <option value="<?=config_item('company_country')?>"><?=config_item('company_country')?></option>
                                                </optgroup>
                                                <optgroup label="<?=lang('other_countries')?>">
                                                        <?php foreach (App::countries() as $country): ?>
                                                        <option value="<?=$country->value?>"><?=$country->value?></option>
                                                        <?php endforeach; ?>
                                                </optgroup>
                                        </select>
                                </div>
                            </div>
                        </div>
                        

                        <!-- START CUSTOM FIELDS -->
                        <div class="tab-pane fade in" id="tab-client-custom">
                        <?php $fields = $this->db->order_by('order','DESC')->where('module','clients')->get('fields')->result(); ?>
                            <?php foreach($fields as $f): ?>
                                <?php $options = json_decode($f->field_options,true); ?>
                                <!-- check if dropdown -->
                                <?php if($f->type == 'dropdown'): ?>

                                <div class="form-group">
                                <label><?=$f->label?> <?=($f->required) ? '<abbr title="required">*</abbr>': '';?></label>
                                <select class="form-control" name="<?='cust_'.$f->name?>" <?=($f->required) ? 'required': '';?> >
                                    <?php foreach($options['options'] as $opt) : ?>
                                    <option value="<?=$opt['label']?>" <?=($opt['checked']) ? 'selected="selected"' : '';?>><?=$opt['label']?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>

                                </div>

                                <!-- Text field -->
                                <?php elseif($f->type == 'text'): ?>

                                    <div class="form-group">
                                    <label><?=$f->label?> <?=($f->required) ? '<abbr title="required">*</abbr>': '';?></label>
                                            <input type="text" name="<?='cust_'.$f->name?>" class="input-sm form-control" <?=($f->required) ? 'required': '';?>>
                                <span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
                                    </div>

                                <!-- Textarea field -->
                                <?php elseif($f->type == 'paragraph'): ?>

                                    <div class="form-group">
                                        <label><?=$f->label?> <?=($f->required) ? '<abbr title="required">*</abbr>': '';?></label>
                                        <textarea name="<?='cust_'.$f->name?>" class="form-control ta" <?=($f->required) ? 'required': '';?>></textarea>
                                <span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
                                    </div>

                                <!-- Radio buttons -->
                                <?php elseif($f->type == 'radio'): ?>
                                    <div class="form-group">
                                        <label><?=$f->label?> <?=($f->required) ? '<abbr title="required">*</abbr>': '';?></label>
                                        <?php foreach($options['options'] as $opt) : ?>
                                <label class="radio-custom">
                                    <input type="radio" name="<?='cust_'.$f->name?>[]" <?=($opt['checked']) ? 'checked="checked"':''; ?> value="<?=$opt['label']?>" <?=($f->required) ? 'required': '';?>> <?=$opt['label']?> </label>
                                        <?php endforeach; ?>
                                <span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
                            </div>

                                <!-- Checkbox field -->
                                <?php elseif($f->type == 'checkboxes'): ?>
                                <div class="form-group">
                                        <label><?=$f->label?> <?=($f->required) ? '<abbr title="required">*</abbr>': '';?></label>

                                <?php foreach($options['options'] as $opt) : ?>
                                    <div class="checkbox">
                                  <label class="checkbox-custom">
                                          <input type="checkbox" name="<?='cust_'.$f->name?>[]" <?=($opt['checked']) ? 'checked="checked"':''; ?> value="<?=$opt['label']?>">
                                     <?=$opt['label']?>
                                </label>
                                    </div>
                                 <?php endforeach; ?>
                                  <span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>

                                </div>
                                <!-- Email Field -->
                                <?php elseif($f->type == 'email'): ?>

                                    <div class="form-group">
                                    <label><?=$f->label?> <?=($f->required) ? '<abbr title="required">*</abbr>': '';?></label>
                                            <input type="email" name="<?='cust_'.$f->name?>" class="input-sm form-control" <?=($f->required) ? 'required': '';?>>
                                <span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
                                    </div>

                                <?php elseif($f->type == 'section_break'): ?>
                                    <hr />
                                <?php endif; ?>


                            <?php endforeach; ?>
                        </div>
                        <!-- End custom fields -->


                    </div>
        </div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-<?=config_item('theme_color');?>"><?=lang('save_changes')?></button>
		</form>
	</div>
</div>
</div>
<script type="text/javascript">
    $('.nav-tabs li a').first().tab('show');
</script>
