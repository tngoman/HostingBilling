<div class="modal-dialog modal-lg">
	<div class="modal-content">
	<?php
		 $attributes = array('class' => 'bs-example form-horizontal');
		  echo form_open(base_url().'blocks/configure',$attributes); ?>	
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> 

		<div class="row">
			<div class="col-md-9"><h2 class="modal-title"><?=$_block->name?></h2></div>
			<div class="col-md-3">
			<?php if($_block->type == 'Module') {
					if(!empty($_block->settings)) 
					{
						$settings = unserialize($_block->settings); 
					}
					else {
						$settings = array('title' => 'no');
					} ?>
					<?=lang('display_title')?>&nbsp; &nbsp;
				 <input type='radio' name='title' value='no' <?=($settings['title'] == 'no') ? 'checked' : '';?>/> <?=lang('no')?> &nbsp;
				 <input type='radio' name='title' value='yes' <?=($settings['title'] == 'yes') ? 'checked' : '';?>/> <?=lang('yes')?>				
				<?php } ?>	
			</div>
			</div>				 
		</div> 
			  
		  <input type="hidden" value="<?=$_block->module?>" name="module">
		  <input type="hidden" value="<?=$_block->type?>" name="type">
		  <input type="hidden" value="<?=$_block->name?>" name="name">
		  <input type="hidden" value="<?=(isset($_block->param)) ? $_block->param : $_block->id?>" name="id">
		<div class="modal-body">
			  <div class="row">
			  	<div class="col-md-9">
				  <img class="img-responsive" src="<?=base_url()?>themes/<?=config_item('active_theme')?>/assets/images/sections.png" alt="Theme Sections" />
				  </div>

				  <div class="col-md-3">
				  	<h4><?=lang('display')?></h4>
				  	<select name="section" class="form-control">
					  <option value=""><?=lang('none')?></option>
					  <?php foreach($blocks as $block) { ?>
						<option value="<?=$block->section?>" 
						<?php if(count($config) > 0) {
							foreach($config as $conf) { 
								if($conf->section == $block->section) {
									echo 'selected';
									break;
								}
							}} ?>><?=$block->name?></option>
					  <?php } ?>
					  </select>

					  <hr>

					 
					  <h4><?=lang('pages')?></h4>	
					  <input type='radio' name='mode' value='show' required <?=(count($config) > 0 && $config[0]->mode == 'show') ? 'checked' : '';?>/> <?=lang('show_in_selected')?><br />
					  <input type='radio' name='mode' value='hide' required <?=(count($config) > 0 && $config[0]->mode == 'hide') ? 'checked' : '';?>/> <?=lang('hide_in_selected')?>				
					  <div id="page_selection">	 
						<?php 

						$pages[] = (object) array('slug' => 'contact', 'title' => lang('contact'));
						foreach ($pages as $key => $p) { ?>
							<div class="checkbox">
								<label class="checkbox-custom">
									<input type="hidden" value="off" name="<?=$p->slug?>" />
									<input <?php 
										if(count($config) > 0) {
											foreach($config as $conf) { 
												if($conf->page == $p->slug) {
													echo 'checked';
												}
											}
										}
									?>
									 name="pages[]" value="<?=$p->slug?>" type="checkbox">
									<?=$p->title?>
								</label>
							</div>
							<?php } ?>

							
						  </div>							

							<h4><?=lang('weight')?></h4>
							<select name="weight" class="form-control"> 
								<?php $weight = 0;
								while($weight < 11) { ?>
									<option value="<?=$weight?>" <?php 
										if(count($config) > 0) {
											foreach($config as $conf) { 
												if($conf->weight == $weight) {
													echo 'selected';
													break;
												}
											}
										}
									?>
									><?=$weight?></option>
								<?php $weight++; } ?>
							</select>
						<hr>
			  </row>			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-<?=config_item('theme_color');?>"><?=lang('save_changes')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
 