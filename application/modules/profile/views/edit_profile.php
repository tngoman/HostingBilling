<div class="box">
<div class="box-body">

    <div class="row">
      <div class="col-lg-6">
         <!-- Profile Form -->
        <section class="panel panel-default">
      <header class="panel-heading font-bold"><?=lang('profile_details')?></header>
      <div class="panel-body">
      <?php
      $profile = User::profile_info(User::get_id());
      $login = User::login_info(User::get_id());
        $attributes = array('class' => 'bs-example form-horizontal');
         echo form_open(uri_string(),$attributes); ?>
         <?php echo validation_errors(); ?>

        <div class="form-group">
          <label class="col-lg-4 control-label"><?=lang('full_name')?> <span class="text-danger">*</span></label>
          <div class="col-lg-8">
          <input type="text" class="form-control" name="fullname" value="<?=$profile->fullname?>" required>
          </div>
        </div>

        <?php if(User::is_staff()){ ?>

          <div class="form-group">
          <label class="col-lg-4 control-label"><?=lang('hourly_rate')?> <span class="text-danger">*</span></label>
          <div class="col-lg-8">
          <input type="text" class="form-control" name="hourly_rate" value="<?=$profile->hourly_rate?>" required>
          </div>
        </div>

        <?php } ?>
        <input type="hidden" value="<?=$profile->company?>" name="co_id">

        <?php
        if ($profile->company > 0) {
            $comp = Client::view_by_id($profile->company);
        ?>  
         <div class="form-group">
          <label class="col-lg-4 control-label"><?=lang('company')?> </label>
          <div class="col-lg-8">
          <input type="text" class="form-control" name="company_data[company_name]" value="<?=(isset($comp->company_name)) ? $comp->company_name : ""?>" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-4 control-label"><?=lang('company_email')?> </label>
          <div class="col-lg-8">
          <input type="text" class="form-control" name="company_data[company_email]" value="<?=(isset($comp->company_email)) ? $comp->company_email : ""?>" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-4 control-label"><?=lang('company_address')?> </label>
          <div class="col-lg-8">
          <input type="text" class="form-control" name="company_data[company_address]" value="<?=(isset($comp->company_address)) ? $comp->company_address : ""?>" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-4 control-label"><?=lang('company_vat')?> </label>
          <div class="col-lg-8">
          <input type="text" class="form-control" name="company_data[vat]" value="<?=(isset($comp->VAT)) ? $comp->VAT : ""?>">
          </div>
        </div>
        <?php } ?>
        <div class="form-group">
          <label class="col-lg-4 control-label"><?=lang('phone')?></label>
          <div class="col-lg-8">
          <input type="text" class="form-control" name="phone" value="<?=$profile->phone?>">
          </div>
        </div>

        <div class="form-group">
            <label class="col-lg-4 control-label"><?=lang('language')?></label>
            <div class="col-lg-8">
                <select name="language" class="form-control">
                <?php foreach (App::languages() as $lang) : ?>
          <option value="<?=$lang->name?>"<?=($profile->language == $lang->name ? ' selected="selected"' : '')?>>
          <?=ucfirst($lang->name); ?></option>
                <?php endforeach; ?>
                </select>
            </div>
        </div>

        
        <div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('locale')?></label>
                <div class="col-lg-8">
                        <select class="select2-option form-control" name="locale">
                        <?php foreach (App::locales() as $loc) : ?>
          <option value="<?=$loc->locale?>"<?=($profile->locale == $loc->locale ? ' selected="selected"' : '')?>>
          <?=$loc->name; ?></option>
                        <?php endforeach; ?>
                        </select>
                </div>
        </div>
        
       
        <?php $currency = App::currencies($comp->currency); ?>
        <div class="form-group">
        <label class="col-lg-4 control-label"><?=lang('currency')?></label>
        <div class="col-lg-8">
            <select name="company_data[currency]" class="form-control">
            <?php foreach (App::currencies() as $cur) : ?>
            <option value="<?=$cur->code?>"<?=($comp->currency == $cur->code ? ' selected="selected"' : '')?>><?=$cur->name?></option>
            <?php endforeach; ?>
            </select>
        </div>
        </div>

        <?php $fields = $this->db->order_by('order','DESC')->where('module','clients')->get('fields')->result(); ?>
        <?php foreach($fields as $f): ?>
            <?php $val = App::field_meta_value($f->name, $profile->company); ?>
            <?php $options = json_decode($f->field_options,true); ?>
            <?php if($f->type == 'dropdown'): ?>

            <div class="form-group">
            <label class="col-lg-4 control-label"><?=$f->label?> <?=($f->required) ? '<span class="text-danger">*</span>': '';?></label>
            <div class="col-lg-8">
            <select class="form-control" name="<?='cust_'.$f->name?>" <?=($f->required) ? 'required': '';?> >
                <option value="<?=$val?>"><?=$val?></option>
                <?php foreach($options['options'] as $opt) : ?>
                <option value="<?=$opt['label']?>" <?=($opt['checked']) ? 'selected="selected"' : '';?>><?=$opt['label']?></option>
                <?php endforeach; ?>
            </select>
            <span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
              </div>
            </div>

            <?php elseif($f->type == 'text'): ?>

                <div class="form-group">
                <label class="col-lg-4 control-label"><?=$f->label?> <?=($f->required) ? '<span class="text-danger">*</span>': '';?></label>
                <div class="col-lg-8">
                        <input type="text" name="<?='cust_'.$f->name?>" class="form-control" value="<?=$val?>" <?=($f->required) ? 'required': '';?>>
            <span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
            </div>
                </div>

            <?php elseif($f->type == 'paragraph'): ?>

                <div class="form-group">
                <label class="col-lg-4 control-label"><?=$f->label?> <?=($f->required) ? '<span class="text-danger">*</span>': '';?></label>
                <div class="col-lg-8">
                    <textarea name="<?='cust_'.$f->name?>" class="form-control ta" <?=($f->required) ? 'required': '';?>><?=$val?></textarea>
            <span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
            </div>
                </div>

            <?php elseif($f->type == 'radio'): ?>
                <div class="form-group">
                <label class="col-lg-4 control-label"><?=$f->label?> <?=($f->required) ? '<span class="text-danger">*</span>': '';?></label>
                <div class="col-lg-8">
                    <?php foreach($options['options'] as $opt) : ?>
                        <?php $sel_val = json_decode($val); ?>
            <label class="radio-custom">
                <input type="radio" name="<?='cust_'.$f->name?>[]" <?=($opt['checked'] || $sel_val[0] == $opt['label']) ? 'checked="checked"':''; ?> value="<?=$opt['label']?>" <?=($f->required) ? 'required': '';?>> <?=$opt['label']?> </label>
                    <?php endforeach; ?>
            <span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
            </div>
        </div>

            <?php elseif($f->type == 'checkboxes'): ?>
            <div class="form-group">
            <label class="col-lg-4 control-label"><?=$f->label?> <?=($f->required) ? '<span class="text-danger">*</span>': '';?></label>
            <div class="col-lg-8">
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
            </div>

            <?php elseif($f->type == 'email'): ?>

                <div class="form-group">
                <label class="col-lg-4 control-label"><?=$f->label?> <?=($f->required) ? '<span class="text-danger">*</span>': '';?></label>
                <div class="col-lg-8">
                        <input type="email" name="<?='cust_'.$f->name?>" value="<?=$val?>" class="input-sm form-control" <?=($f->required) ? 'required': '';?>>
            <span class="help-block"><?=isset($options['description']) ? $options['description'] : ''?></span>
                </div>
              </div>
            <?php elseif($f->type == 'section_break'): ?>
                <hr />
            <?php endif; ?>
        <?php endforeach; ?> 
        
        <button type="submit" class="btn btn-sm btn-dark"><?=lang('update_profile')?></button>
      </form>


      <h4 class="page-header"><?=lang('change_email')?></h4>

       <?php
       $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open(base_url().'auth/change_email',$attributes); ?>
        <input type="hidden" name="r_url" value="<?=uri_string()?>">

        <div class="form-group">
          <label class="col-lg-4 control-label"><?=lang('current_email')?></label>
          <div class="col-lg-8">
          <input type="text" class="form-control" name="" value="<?=User::login_info(User::get_id())->email;?>" readonly="readonly">
          </div>
        </div>


     <div class="form-group">
          <label class="col-lg-4 control-label"><?=lang('password')?></label>
          <div class="col-lg-8">
          <input type="password" class="form-control" name="password" placeholder="<?=lang('password')?>" required>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-4 control-label"><?=lang('new_email')?></label>
          <div class="col-lg-8">
          <input type="email" class="form-control" name="email" placeholder="<?=lang('new_email')?>" required>
          </div>
        </div>
        
        <button type="submit" class="btn btn-sm btn-success"><?=lang('change_email')?></button>
      </form>


    </div>
  </section>
  <!-- /profile form -->
</div>
<div class="col-lg-6">
      
        <!-- Account Form -->
        <section class="panel panel-default">
      <header class="panel-heading font-bold"><?=lang('account_details')?></header>
      <div class="panel-body">
        <?php
        echo form_open(base_url().'auth/change_password'); ?>
        <input type="hidden" name="r_url" value="<?=uri_string()?>">
        <div class="form-group">
          <label><?=lang('old_password')?> <span class="text-danger">*</span></label>
          <input type="password" class="form-control" name="old_password" placeholder="<?=lang('old_password')?>" required>
        </div>
        <div class="form-group">
          <label><?=lang('new_password')?> <span class="text-danger">*</span></label>
          <input type="password" class="form-control" name="new_password" placeholder="<?=lang('new_password')?>" required>
        </div>
         <div class="form-group">
          <label><?=lang('confirm_password')?> <span class="text-danger">*</span></label>
          <input type="password" class="form-control" name="confirm_new_password" placeholder="<?=lang('confirm_password')?>" required>
        </div>
        
        <button type="submit" class="btn btn-sm btn-dark"><?=lang('change_password')?></button>
      </form>

<h4 class="page-header"><?=lang('avatar_image')?></h4>

       <?php
       $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open_multipart(base_url().'profile/changeavatar',$attributes); ?>
        <input type="hidden" name="r_url" value="<?=uri_string()?>">

        <div class="form-group">
                      <label class="col-lg-3 control-label"><?=lang('use_gravatar')?></label>
                      <div class="col-lg-8">
                        <label class="switch">
                          <input type="checkbox" <?php if($profile->use_gravatar == 'Y'){ echo "checked=\"checked\""; } ?> name="use_gravatar">
                          <span></span>
                        </label>
                      </div>
        </div>

       <div class="form-group">
        <label class="col-lg-3 control-label"><?=lang('avatar_image')?></label>
        <div class="col-lg-9">
          <input type="file" class="filestyle" data-buttonText="<?=lang('choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="userfile">
        </div>
      </div>
        <button type="submit" class="btn btn-sm btn-success"><?=lang('change_avatar')?></button>
      </form>

      <h4 class="page-header"><?=lang('change_username')?></h4>

       <?php
       $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open(base_url().'auth/change_username',$attributes); ?>
        <input type="hidden" name="r_url" value="<?=uri_string()?>">
     
        <div class="form-group">
          <label class="col-lg-4 control-label"><?=lang('new_username')?></label>
          <div class="col-lg-7">
          <input type="text" class="form-control" name="username" placeholder="<?=lang('new_username')?>" required>
          </div>
        </div>
        
        <button type="submit" class="btn btn-sm btn-danger"><?=lang('change_username')?></button>
      </form>


    </div>
  </section>
  <!-- /Account form -->
  </div>
    </div>
  </div> 