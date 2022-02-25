<?php 
$user_login = User::login_info($id);
$profile = User::profile_info($id);
?>
          <div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title text-danger"><?=lang('edit_user')?> - <?php echo User::displayName($id);?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'contacts/update',$attributes); ?>
          
		<div class="modal-body">
			 <input type="hidden" name="user_id" value="<?=$profile->user_id?>">
			 <input type="hidden" name="company" value="<?=$profile->company?>">
			 
			 <div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('full_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$profile->fullname?>" name="fullname">
				</div>
				</div>
          		<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('email')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="email" class="form-control" value="<?=$user_login->email?>" name="email" required>
				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('phone')?> </label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$profile->phone?>" name="phone">
				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('mobile_phone')?> </label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$profile->mobile?>" name="mobile">
				</div>
                                </div>
				<div class="form-group">
				<label class="col-lg-4 control-label">Skype</label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$profile->skype?>" name="skype">
				</div>
                </div>


				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('language')?></label>
					<div class="col-lg-5">
						<select name="language" class="form-control">
						<?php foreach (App::languages() as $lang) : ?>
						<option value="<?=$lang->name?>"<?=($profile->language == $lang->name ? ' selected="selected"' : '')?>><?=  ucfirst($lang->name)?></option>
						<?php endforeach; ?>
						</select>
					</div>
				</div>
			
			
				<div class="form-group">
						<label class="col-lg-4 control-label"><?=lang('locale')?></label>
						<div class="col-lg-5">
								<select class="select2-option form-control" name="locale">
								<?php foreach (App::locales() as $loc) : ?>
								<option lang="<?=$loc->code?>" value="<?=$loc->locale?>"<?=(config_item('locale') == $loc->locale ? ' selected="selected"' : '')?>><?=$loc->name?></option>
								<?php endforeach; ?>
								</select>
						</div>
				</div>
			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-<?=config_item('theme_color');?>"><?=lang('save_changes')?></button>
		</form>		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->