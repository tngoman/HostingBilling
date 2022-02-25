<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-danger"> 
		<button type="button" class="close" data-dismiss="modal">&times;</button> 
		<h4 class="modal-title"><?=lang('delete_slide')?></h4>
		</div><?php
			echo form_open(base_url().'sliders/delete_slide'); ?>
		<div class="modal-body">
			<p><?=lang('delete_slide_warning')?></p>
			
			<input type="hidden" name="slide_id" value="<?=$slide->slide_id?>">
          	<input type="hidden" name="current_image" value="<?=$slide->image?>">

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-<?=config_item('theme_color')?>"><?=lang('delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->