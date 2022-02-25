<div class="box"> 
    <div class="box-body"> 
    <div class="container"> 
    <h2><?=lang('custom_block')?> <a href="<?=base_url()?>blocks/add" class="btn btn-info pull-right"><?=lang('rict_text_format')?></a></h2>
		<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'blocks/add',$attributes); ?>   

          		<div class="form-group">
				<label class="control-label"><?=lang('name')?> <span class="text-danger">*</span></label>
		 			<input type="text" class="form-control" name="name">
				</div> 

				<div class="form-group">
				<label class="control-label"><?=lang('type')?> <span class="text-danger">*</span></label>
					<select type="text" class="form-control" name="format">
						<option value="js" class="form-control">HTML & Javascript - <?=lang('including_tags')?></optopn>
						<option value="php" class="form-control">PHP - <?=lang('excluding_tags')?></optopn>						
					</select>
				</div>

				<div class="form-group">
				<label class="control-label"><?=lang('content')?> <span class="text-danger">*</span></label>
						<textarea class="form-control" name="content" rows="10" cols="50"></textarea>
				</div>
				 
		<div class="box-footer"><button type="submit" class="btn btn-<?=config_item('theme_color');?> pull-right"><?=lang('save')?></button>		
    </div>
    </form>
    </div>
  </div>
</div>