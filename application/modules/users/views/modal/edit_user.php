<?php $company = User::profile_info($this->session->userdata('user_id')); ?>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('edit_user')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open_multipart(base_url().'users/account/update',$attributes); ?>
          <?php $user = User::view_user($id); $info = User::profile_info($id); ?>
		<div class="modal-body">
			 <input type="hidden" name="user_id" value="<?=$user->id?>">

			 <div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('full_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$info->fullname?>" name="fullname">
				</div>
				</div>

				<div class="form-group">
                                <label class="col-lg-3 control-label"><?=lang('company')?></label>
                                <div class="col-lg-7">
                                    <select class="select2-option w_210" name="company" >
                                    <optgroup label="<?=lang('default_company')?>">
                                        <?php if($info->company ==  $company->company){ ?>
                                        <option value="<?=$company->company?>" selected="selected"><?=config_item('company_name')?></option>
                                        <?php }else{ ?>
                                        <option value="<?=$company->company?>"><?=config_item('company_name')?></option>
                                        <?php } ?>
                                    </optgroup>
                                    <optgroup label="<?=lang('other_companies')?>">
                                        <?php foreach (Client::get_all_clients() as $company){ ?>
                                        <option value="<?=$company->co_id?>"<?=($info->company == $company->co_id ? ' selected="selected"' : '')?>><?=$company->company_name?></option>
                                        <?php } ?>
                                    </optgroup>
 
                                    </select>
                                </div>
                            </div>


			      <?php
			      if (User::get_role($user->id) == 'staff' || User::get_role($user->id) == 'admin') { ?>
			      <div class="form-group">
			        <label class="col-lg-3 control-label"><?=lang('department')?> </label>
			        <div class="col-lg-8">
			        <select  name="department[]" class="select2-option w_200" multiple="multiple">

			          <?php
			          $departments = $this->db->get('departments')->result();
			          $dep = json_decode($info->department,TRUE);
			          if (!empty($departments)){
			            foreach ($departments as $d){ ?>

		<option value="<?=$d->deptid?>" <?=($d->deptid == $info->department || (is_array($dep) && in_array($d->deptid, $dep))) ? ' selected="selected"' : ''?>>

			            <?=$d->deptname?> </option>
			            <?php } } ?>
			          </select>
			          <a href="<?=site_url()?>settings/?settings=departments" class="btn btn-sm btn-danger">Add Department</a>
			          </div>
			      </div>
			      <?php } ?>

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('phone')?> </label>
				<div class="col-lg-4">
					<input type="text" class="form-control" value="<?=$info->phone?>" name="phone">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('mobile_phone')?> </label>
				<div class="col-lg-4">
					<input type="text" class="form-control" value="<?=$info->mobile?>" name="mobile">
				</div>
                                </div>
				<div class="form-group">
				<label class="col-lg-3 control-label">Skype</label>
				<div class="col-lg-6">
					<input type="text" class="form-control" value="<?=$info->skype?>" name="skype">
			</div>
			</div>


			<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('language')?></label>
				<div class="col-lg-5">
					<select name="language" class="form-control">
					<?php foreach (App::languages() as $lang) : ?>
					<option value="<?=$lang->name?>"<?=($info->language == $lang->name ? ' selected="selected"' : '')?>><?= ucfirst($lang->name)?></option>
					<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="form-group">
					<label class="col-lg-3 control-label"><?=lang('locale')?></label>
					<div class="col-lg-5">
							<select class="select2-option form-control" name="locale">
							<?php foreach (App::locales() as $loc) : ?>
							<option lang="<?=$loc->code?>" value="<?=$loc->locale?>"<?=($info->locale == $loc->locale ? ' selected="selected"' : '')?>>
							<?=$loc->name?></option>
							<?php endforeach; ?>
							</select>
					</div>
			</div>
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-<?=config_item('theme_color');?>"><?=lang('save_changes')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<script type="text/javascript">
(function($){
   "use strict";
	$(".select2-option").select2();
})(jQuery); 
</script>
