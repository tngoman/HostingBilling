		<?php
		$attributes = array('class' => 'bs-example form-horizontal');
		echo form_open(base_url() . 'settings/fields/module', $attributes);
		?>
			<div class="form-group">
						<label class="col-lg-2 control-label"><?= lang('module') ?> <span class="text-danger">*</span> </label>
						<div class="col-lg-3">
							<div class="m-b">
								<select name="module" class="form-control" required id="module">
									<option value="clients">Clients</option>
									<option value="tickets">Tickets</option>					
								</select>
							</div>
						</div>
					</div>

					<div class="select_department hidden">
						<div class="form-group">
							<label class="col-lg-2 control-label"><?= lang('department') ?> <span class="text-danger">*</span> </label>
							<div class="col-lg-3">
								<div class="m-b">
									<select name="department" class="form-control">
										<?php $dept = $this->db->get('departments')->result(); ?>
										<?php foreach($dept as $d) : ?>
											<option value="<?=$d->deptid?>"><?=$d->deptname?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>

					</div>
		 
				<div class="text-center">
					<button type="submit" class="btn btn-sm btn-<?=config_item('theme_color');?>"><?=lang('save_changes')?></button>
				</div>
		 
		</form>
 
<script type="text/javascript">
	(function($){
	"use strict";
		$(document).ready(function(){
			$("#module").change(function(){
				$(this).find("option:selected").each(function(){
					if($(this).attr("value")=="tickets"){
						$(".select_department").show();
					}
					else{
						$(".select_department").hide();
					}
				});
			}).change();
		});
	})(jQuery);
</script>
