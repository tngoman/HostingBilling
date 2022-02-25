<?php $block = $block[0]; ?>
<div class="box"> 
    <div class="box-body"> 
    <div class="container"> 
		<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'blocks/edit',$attributes); ?>   

		  		<input type="hidden" name="id" value="<?=$block->id?>">

          		<div class="form-group">
				<label class="control-label"><?=lang('name')?> <span class="text-danger">*</span></label>
		 			<input type="text" class="form-control" name="name" value="<?=$block->name?>">
				</div> 

				<div class="form-group">
				<label class="control-label"><?=lang('type')?> <span class="text-danger">*</span></label>
					<select type="text" class="form-control" name="format">
						<option value="js" class="form-control" <?=($block->format == 'js') ? 'selected' : '';?>>HTML & Javascript - <?=lang('including_tags')?></option>
						<option value="php" class="form-control" <?=($block->format == 'php') ? 'selected' : '';?>>PHP - <?=lang('excluding_tags')?></option>	
					</select>
				</div>

				<div class="form-group">
				<label class="control-label"><?=lang('content')?> <span class="text-danger">*</span></label>
						<textarea class="form-control" name="content" rows="10" cols="50"><?=$block->code?></textarea>
				</div>
				 
		<div class="box-footer"><button type="submit" class="btn btn-<?=config_item('theme_color');?> pull-right"><?=lang('update')?></button>		
    </div>
    </form>
    </div>
  </div>
</div>