<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?=lang('edit_client')?></h4>
        </div><?php $i = Client::view_by_id($company); ?>

<?php echo form_open(base_url().'companies/update'); ?>
        <input class="hidden">
        <input type="password" class="hidden">
        <div class="modal-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li><a class="active" data-toggle="tab" href="#tab-client-general"><?=lang('details')?></a></li>
                        <li><a data-toggle="tab" href="#tab-client-contact"><?=lang('address')?></a></li> 
                        <li><a data-toggle="tab" href="#tab-client-custom"><?=lang('custom_fields')?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab-client-general">
                            <input type="hidden" name="company_ref" value="<?=$i->company_ref?>">
                            <input type="hidden" name="co_id" value="<?=$i->co_id?>">
                            <div class="row">
                                <div class="col-md-6">

                                        <div class="form-group">
                                            <label><?php if ($i->individual == 0) { echo lang('company_name'); } else { echo lang('full_name'); } ?><span class="text-danger">*</span></label>
                                            <input type="text" name="company_name" value="<?=$i->company_name?>" class="input-sm form-control" required>
                                        </div>

                                        <div class="form-group">
                                                <label><?=lang('email')?> <span class="text-danger">*</span></label>
                                                <input type="email" name="company_email" value="<?=$i->company_email?>" class="input-sm form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label><?=lang('phone')?> </label>
                                            <input type="text" value="<?=$i->company_phone?>" name="company_phone"  class="input-sm form-control">
                                        </div>

                                        <div class="form-group">
                                            <label><?=lang('credit_balance')?> (<?=config_item('default_currency')?>)</label>
                                            <input type="text" value="<?=$i->transaction_value?>" name="transaction_value"  class="input-sm form-control">
                                        </div>
                                </div>

                                <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?=lang('vat')?> </label>
                                            <input type="text" value="<?=$i->VAT?>" name="VAT" class="input-sm form-control">
                                        </div>

                                        <div class="form-group">
                                            <label><?=lang('mobile_phone')?> </label>
                                            <input type="text" value="<?=$i->company_mobile?>" name="company_mobile"  class="input-sm form-control">
                                        </div>

                                        <div class="form-group">
                                                <label><?=lang('fax')?> </label>
                                                <input type="text" value="<?=$i->company_fax?>" name="company_fax"  class="input-sm form-control">
                                        </div>

                                            
                                    <?php $currency = App::currencies($i->currency); ?>
                                <div class="form-group">
                                    <label><?=lang('client')?> <?=lang('currency')?></label>
                                    <select name="currency" class="form-control">
                                    <?php foreach (App::currencies() as $cur) : ?>
                                    <option value="<?=$cur->code?>"<?=($currency->code == $cur->code ? ' selected="selected"' : '')?>><?=$cur->name?></option>
                                    <?php endforeach; ?>
                                    </select>
                                </div>
                                        
                                        
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?=lang('notes')?></label>
                    <textarea name="notes" class="form-control ta"><?=$i->notes;?></textarea>
                            </div>

                        </div>
                        <div class="tab-pane fade in" id="tab-client-contact">                          
                            
                            <div class="clearfix"></div>
                            <div class="form-group">
                                    <label><?=lang('address')?></label>
                                    <textarea name="company_address" class="form-control ta"><?=$i->company_address?></textarea>
                            </div>
                            <div class="form-group col-md-6 no-gutter-left">
                                    <label><?=lang('city')?> </label>
                                    <input type="text" value="<?=$i->city?>" name="city" class="input-sm form-control">
                            </div>
                            <div class="form-group col-md-6 no-gutter-right">
                                    <label><?=lang('zip_code')?> </label>
                                    <input type="text" value="<?=$i->zip?>" name="zip" class="input-sm form-control">
                            </div>
                            <div class="row">
                            <div class="form-group col-md-6">
                                    <label><?=lang('state_province')?> </label>
                                    <input type="text" value="<?=$i->state?>" name="state" class="input-sm form-control">
                            </div>
                            <div class="form-group col-md-6 no-gutter-right">

                                <label><?=lang('language')?></label>
                                <select name="language" class="form-control">
                                <?php foreach (App::languages() as $lang) : ?>
                                <option value="<?=$lang->name?>"<?=($i->language == $lang->name ? ' selected="selected"' : '')?>><?=  ucfirst($lang->name)?></option>
                                <?php endforeach; ?>
                                </select>

                                <label><?=lang('country')?> </label>
                                <select class="form-control w_180" name="country" >
                                        <optgroup label="<?=lang('selected_country')?>">
                                                <option value="<?=$i->country?>"><?=$i->country?></option>
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
                                <?php $val = App::field_meta_value($f->name, $company); ?>
                                <?php $options = json_decode($f->field_options,true); ?>
                                <!-- check if dropdown -->
                                <?php if($f->type == 'dropdown'): ?>

                                <div class="form-group">
                                <label><?=$f->label?> <?=($f->required) ? '<abbr title="required">*</abbr>': '';?></label>
                                <select class="form-control" name="<?='cust_'.$f->name?>" <?=($f->required) ? 'required': '';?> >
                                    <option value="<?=$val?>"><?=$val?></option>
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
                                            <input type="text" name="<?='cust_'.$f->name?>" class="input-sm form-control" value="<?=$val?>" <?=($f->required) ? 'required': '';?>>
                                <span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
                                    </div>

                                <!-- Textarea field -->
                                <?php elseif($f->type == 'paragraph'): ?>

                                    <div class="form-group">
                                        <label><?=$f->label?> <?=($f->required) ? '<abbr title="required">*</abbr>': '';?></label>
                                        <textarea name="<?='cust_'.$f->name?>" class="form-control ta" <?=($f->required) ? 'required': '';?>><?=$val?></textarea>
                                <span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
                                    </div>

                                <!-- Radio buttons -->
                                <?php elseif($f->type == 'radio'): ?>
                                    <div class="form-group">
                                        <label><?=$f->label?> <?=($f->required) ? '<abbr title="required">*</abbr>': '';?></label>
                                        <?php foreach($options['options'] as $opt) : ?>
                                            <?php $sel_val = json_decode($val); ?>
                                <label class="radio-custom">
                                    <input type="radio" name="<?='cust_'.$f->name?>[]" <?=($opt['checked'] || $sel_val[0] == $opt['label']) ? 'checked="checked"':''; ?> value="<?=$opt['label']?>" <?=($f->required) ? 'required': '';?>> <?=$opt['label']?> </label>
                                        <?php endforeach; ?>
                                <span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
                            </div>

                                <!-- Checkbox field -->
                                <?php elseif($f->type == 'checkboxes'): ?>
                                <div class="form-group">
                                        <label><?=$f->label?> <?=($f->required) ? '<abbr title="required">*</abbr>': '';?></label>

                                <?php foreach($options['options'] as $opt) : ?>
                                    <?php $sel_val = json_decode($val); ?>
                                    <div class="checkbox">
                                  <label class="checkbox-custom">
                                      <?php if(is_array($sel_val)) : ?>
                                          <input type="checkbox" name="<?='cust_'.$f->name?>[]" <?=($opt['checked'] || in_array($opt['label'], $sel_val)) ? 'checked="checked"':''; ?> value="<?=$opt['label']?>">
                                      <?php else: ?>
                                          <input type="checkbox" name="<?='cust_'.$f->name?>[]" <?=($opt['checked']) ? 'checked="checked"':''; ?> value="<?=$opt['label']?>">
                                      <?php endif; ?>
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
                                            <input type="email" name="<?='cust_'.$f->name?>" value="<?=$val?>" class="input-sm form-control" <?=($f->required) ? 'required': '';?>>
                                <span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
                                    </div>

                                <?php elseif($f->type == 'section_break'): ?>
                                    <hr />
                                <?php endif; ?>


                            <?php endforeach; ?>
                        </div>

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
