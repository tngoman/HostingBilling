<?php
$attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'settings/add_custom_field',$attributes); ?>

          <div class="form-group">
				<label class="col-lg-2 control-label"><?=lang('department')?> <span class="text-danger">*</span> </label>
				<div class="col-lg-6">
					<div class="m-b"> 
					<select name="targetdept" class="form-control" required >
					<?php 
					$departments = $this -> db -> where(array('deptid >'=>'0')) -> get('departments') -> result();
					if (!empty($departments)) {
						foreach ($departments as $d): ?>
                                            <option value="<?=$d->deptid?>"><?=ucfirst($d->deptname)?></option>
					<?php endforeach; } ?>
					</select> 
					</div> 
				</div>
			</div>
<button type="submit" class="btn btn-sm btn-info"><?=lang('select_department')?></button>

</form>