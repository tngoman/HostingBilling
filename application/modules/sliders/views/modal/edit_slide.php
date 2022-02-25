<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('edit_slide')?></h4>
		</div>

	<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open_multipart(base_url().'sliders/edit_slide/',$attributes); ?>
          <input type="hidden" name="slide_id" value="<?=$slide->slide_id?>">
          <input type="hidden" name="current_image" value="<?=$slide->image?>">
		<div class="modal-body">

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=lang('title')?></label>
                    <div class="col-lg-9">
                    <input name="title" class="form-control" value="<?=$slide->title?>"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=lang('description')?> <br />

                    <?php if(!empty($slide->image)) {?>
                        <img src="<?=base_url()?>resource/uploads/<?=$slide->image?>" class="edit_thumb" />
                        <?php } ?>
                    
                    </label>
                    <div class="col-lg-9">
                    <textarea name="description" class="form-control ta"> <?=$slide->description?></textarea>
                    </div>
                </div>               

                <div id="file_container">
                    <div class="form-group">
                        <div class="col-lg-offset-3 col-lg-9">
                            <input type="file" name="images[]">
                        </div>
                    </div>
                </div>

		<div class="modal-footer"> 
                    <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
                    <button type="submit" class="btn btn-<?=config_item('theme_color');?>"><?=lang('save')?></button>
		</form>
		</div>
	        </div>
        </div>

 
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

 