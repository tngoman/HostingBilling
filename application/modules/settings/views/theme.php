	<!-- Start Form -->
		<?php
		$attributes = array('class' => 'bs-example form-horizontal');
		echo form_open_multipart('settings/update', $attributes); ?>
			 
					<?php echo validation_errors(); ?>
					<input type="hidden" name="settings" value="<?=$load_setting?>">

					<input type="hidden" name="top_bar-color" value="<?=config_item('top_bar_color')?>">


					<div class="form-group">
						<label class="col-lg-3 control-label"><?=lang('skin')?></label>
						<div class="col-lg-3">
							 <ul id="skins" class="list-unstyled clearfix"></ul>
						</div>
					</div>


					<div class="form-group">
						<label class="col-lg-3 control-label"><?=lang('website_theme')?> </label>
					<div class="col-lg-3">
							<select name="active_theme" class="form-control">
								<option value="original" <?=(config_item('active_theme') == 'original') ? 'selected' : ''?>>Original</option>
								<option value="custom" <?=(config_item('active_theme') == 'custom') ? 'selected' : ''?>>Custom</option>
							</select>
						</div> 
					</div>
				
				
					<div class="form-group">
						<label class="col-lg-3 control-label"><?=lang('system_font')?> </label>
						<div class="col-lg-3">
							<?php $font = config_item('system_font'); ?>
							<select name="system_font" class="form-control">
								<option value="open_sans"<?=($font == "open_sans" ? ' selected="selected"' : '')?>>Open Sans</option>
								<option value="open_sans_condensed"<?=($font == "open_sans_condensed" ? ' selected="selected"' : '')?>>Open Sans Condensed</option>
								<option value="roboto"<?=($font == "roboto" ? ' selected="selected"' : '')?>>Roboto</option>
								<option value="roboto_condensed"<?=($font == "roboto_condensed" ? ' selected="selected"' : '')?>>Roboto Condensed</option>
								<option value="ubuntu"<?=($font == "ubuntu" ? ' selected="selected"' : '')?>>Ubuntu</option>
								<option value="lato"<?=($font == "lato" ? ' selected="selected"' : '')?>>Lato</option>
								<option value="oxygen"<?=($font == "oxygen" ? ' selected="selected"' : '')?>>Oxygen</option>
								<option value="pt_sans"<?=($font == "pt_sans" ? ' selected="selected"' : '')?>>PT Sans</option>
								<option value="source_sans"<?=($font == "source_sans" ? ' selected="selected"' : '')?>>Source Sans Pro</option>
							</select>
						</div>
					</div>
			 
					<div class="form-group">
						<label class="col-lg-3 control-label"><?=lang('theme_color')?></label>
						<div class="col-lg-3">
							<?php $theme = config_item('theme_color'); ?>
							<select name="theme_color" class="form-control">
								<option value="success" <?=($theme == "success" ? ' selected="selected"' : '')?>>Success</option>
								<option value="info" <?=($theme == "info" ? ' selected="selected"' : '')?>>Info</option>
								<option value="danger" <?=($theme == "danger" ? ' selected="selected"' : '')?>>Danger</option>
								<option value="warning" <?=($theme == "warning" ? ' selected="selected"' : '')?>>Warning</option>
								<option value="dark" <?=($theme == "dark" ? ' selected="selected"' : '')?>>Dark</option>
								<option value="primary" <?=($theme == "primary" ? ' selected="selected"' : '')?>>Primary</option>
							</select>
						</div>
					</div>

				

					<div class="form-group">
						<label class="col-lg-3 control-label"><?=lang('logo_or_icon')?></label>
						<div class="col-lg-3">
							<select name="logo_or_icon" class="form-control">
								<?php $logoicon = config_item('logo_or_icon'); ?>
								<option value="icon_title"<?=($logoicon == "icon_title" ? ' selected="selected"' : '')?>><?=lang('icon')?> & <?=lang('site_name')?></option>
								<option value="icon"<?=($logoicon == "icon" ? ' selected="selected"' : '')?>><?=lang('icon')?></option>
								<option value="logo_title"<?=($logoicon == "logo_title" ? ' selected="selected"' : '')?>><?=lang('logo')?> & <?=lang('site_name')?></option>
								<option value="logo"<?=($logoicon == "logo" ? ' selected="selected"' : '')?>><?=lang('logo')?></option>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-lg-3 control-label"><?=lang('site_icon')?></label>
                                                        <div class="input-group iconpicker-container col-lg-3">
                                                        <span class="input-group-addon"><i class="fa <?=config_item('site_icon')?>"></i></span>
                                                        <input id="site-icon" name="site_icon" type="text" value="<?=config_item('site_icon')?>" class="form-control icp icp-auto iconpicker-element iconpicker-input" data-placement="bottomRight">
						</div>
					</div>


					<div class="form-group">
						<label class="col-lg-3 control-label"><?=lang('company_logo')?></label>
						<div class="col-lg-2">
							<input type="file" class="filestyle" data-buttonText="<?=lang('choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="logofile">
						</div>
						<div class="col-lg-7">
							<?php if (config_item('company_logo') != '') : ?>
							<div class="settings-image"><img src="<?=base_url()?>resource/images/<?=config_item('company_logo')?>" /></div>
							<?php endif; ?>
						</div>
					</div>
		


					<div class="form-group">
						<label class="col-lg-3 control-label"><?=lang('favicon')?></label>
						<div class="col-lg-2">
							<input type="file" class="filestyle" data-buttonText="<?=lang('choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="iconfile">
						</div>
						<div class="col-lg-7">
							<?php if (config_item('site_favicon') != '') : ?>
							<div class="settings-image"><img src="<?=base_url()?>resource/images/<?=config_item('site_favicon')?>" /></div>
							<?php endif; ?>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-3 control-label"><?=lang('apple_icon')?></label>
						<div class="col-lg-2">
							<input type="file" class="filestyle" data-buttonText="<?=lang('choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="appleicon">
						</div>
						<div class="col-lg-7">
							<?php if (config_item('site_appleicon') != '') : ?>
							<div class="settings-image"><img src="<?=base_url()?>resource/images/<?=config_item('site_appleicon')?>" /></div>
							<?php endif; ?>
						</div>
					</div>

			  
	 
					<div class="text-center">
						<button type="submit" class="btn btn-sm btn-<?=config_item('theme_color');?>"><?=lang('save_changes')?></button>
					</div>
		 
		</form>
 
	<!-- End Form -->
 