<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('edit_custom_field')?></h4>
		</div>
		<?php
					if (!empty($field_info)) {
					foreach ($field_info as $key => $f) { ?>
					<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'settings/edit_custom_field',$attributes); ?>
		<div class="modal-body">
			 <input type="hidden" name="deptid" value="<?=$f->deptid?>">

          		<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('custom_field_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$f->name?>" required name="name">
				</div>
				</div>

				<div class="form-group">
                <label class="col-lg-4 control-label"><?=lang('field_type')?> <span class="text-danger">*</span> </label>
                <div class="col-lg-6">
                    <select name="type" class="form-control">
                    <option value="text">Text Field</option>
                    </select> 
                </div>
            </div>

				<div class="form-group">
                      <label class="col-lg-4 control-label"><?=lang('delete_custom_field')?></label>
                      <div class="col-lg-8">
                        <label class="switch">
                          <input type="checkbox" name="delete_field">
                          <span></span>
                        </label>
                      </div>
                    </div>

				<input type="hidden" name="id" value="<?=$f->id?>">
			
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
		<button type="submit" class="btn btn-primary"><?=lang('save_changes')?></button>
		</form>
		<?php } } ?>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->