<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('activate_account')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'accounts/activate',$attributes); ?>
		<div class="modal-body">
			
				<div class="form-group">
						<label class="col-lg-5 control-label"><?=lang('server')?></label>
						<div class="col-lg-5">
						<select id="server" name="server" class="form-control m-b">
							<?php $default_server = $this->db->where(array('id'=> $item->server))->get('servers')->row();
							foreach ($servers as $server) { ?>
							<option value="<?=$server->id?>" <?=(isset($default_server->id) && $default_server->id == $server->id) ? 'selected' : ''?>><?=$server->name?> (<?=$server->type?>)</option>
							<?php } ?>
						</select>
						</div>

						<label class="col-lg-5 control-label"><?=lang('send_details_to_client')?></label>
						<div class="col-lg-5">
							<label class="switch">
									<input type="hidden" value="off" name="send_details" />
									<input type="checkbox" name="send_details">									
								<span></span>
							</label>
						</div>

						<label class="col-lg-5 control-label"><?=lang('create_controlpanel')?></label>
						<div class="col-lg-5">
						<label class="switch">
									<input type="hidden" value="off" name="create_controlpanel" />
									<input type="checkbox" name="create_controlpanel">									
								<span></span>
							</label>
						</div>
 
						<input type="hidden" name="id" value="<?=$item->id?>">						
					
				</div>
				<h3><?=$item->item_name?> - <?=$item->domain?></h3>
				<table class="table table-bordered table-striped">		
						<thead><tr><th><?=lang('username')?></th><th><?=lang('password')?></th></thead>
						<tbody>						
							<tr>
							<td><input type="text" value="<?=$item->username?>" name="username" class="form-control"></td>
							<td><input type="text" value="<?=$item->password?>" name="password" class="form-control"></td>
							 </tr>
						<?php  ?>
					</tbody>
				</table>
				
				 
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-<?=config_item('theme_color');?>"><?=lang('activate')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
