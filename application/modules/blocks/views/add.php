<div class="box"> 
    <div class="box-body"> 
    <div class="container"> 
	<h2><?=lang('custom_block')?>
	<?php if(config_item('allow_js_php_blocks') == "TRUE") { ?>
	 <a href="<?=base_url()?>blocks/add_code" class="btn btn-warning pull-right"><?=lang('code_format')?></a>
	<?php } ?>
	</h2> 
		<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'blocks/add',$attributes); ?>
                 <input type="hidden" name="format" value="rich_text">
                 
          		<div class="form-group">
				<label class="control-label"><?=lang('name')?> <span class="text-danger">*</span></label>
		 			<input type="text" class="form-control" name="name">
				</div> 

				<div class="form-group">
				<label class="control-label"><?=lang('content')?> <span class="text-danger">*</span></label>
						<textarea class="form-control foeditor" name="content"></textarea>
				</div>
				 
		<div class="box-footer"><button type="submit" class="btn btn-<?=config_item('theme_color');?> pull-right"><?=lang('save')?></button>		
    </div>
    </form>
    </div>
  </div>
</div>