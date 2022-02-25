<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('activate_order')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'orders/activate',$attributes); ?>
		<div class="modal-body">

		<input type="hidden" name="o_id" value="<?=$order[0]->o_id?>"> 
		
				<?php if($order[0]->o_id > 0) { ?>
					<h3><?=lang('upgrade_downgrade')?> (<?=$order[0]->domain?>)</h3>
					  <h5> <?=$order[0]->item_desc?> </h5>	  

				<?php } else { ?>
			 
						<input type="hidden" name="client_id" value="<?=$order[0]->client_id?>">
						<input type="hidden" name="inv_id" value="<?=$order[0]->invoice_id?>">
			 
			 
				<h3><?=lang('hosting')?></h3>
				<table class="table table-bordered table-striped">		
						<thead><tr><th><?=lang('package')?></th><th><?=lang('username')?></th><th><?=lang('password')?></th><th><?=lang('create_controlpanel')?></th><th style="width:180px;text-align:center;"><?=lang('server')?></th><th><?=lang('send_details')?></th></thead>
						<tbody>
						<?php foreach($order as $item) { 
							if($item->type == 'hosting') { ?>	 
							<tr>
							<td><?=$item->item_name?> - <?=$item->domain?></td>
							<td>							
							<input type="hidden" name="service[]" value="<?=$item->item_name?>">
							<input type="hidden" name="hosting[]" value="<?=$item->id?>">
							<input type="hidden" name="hosting_status[]" value="<?=$item->status_id?>">
							<input type="hidden" name="hosting_domain[]" value="<?=$item->domain?>">
							<input type="hidden" name="hosting_item_id[]" value="<?=$item->item_parent?>">
							<input type="text" value="<?=$item->username?>" name="username[]" class="form-control"></td>
							<td><input type="text" value="<?=$item->password?>" name="password[]" class="form-control">
							</td>					
							<td><?php if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts')) { ?>
								<label class="switch">
									<input type="hidden" value="off" name="<?=$item->username?>_controlpanel" />
									<input type="checkbox" name="<?=$item->username?>_controlpanel">									
								<span></span>
							</label>
							<?php } ?>
							</td>
							<td>
							<select id="server" name="server[]" class="form-control m-b">							
								<?php
								$parent = $this->db->where('item_id', $item->item_parent)->get('items_saved')->row(); 
								$default_server = $this->db->where('id', $parent->server)->get('servers')->row(); 
								foreach ($servers as $server) { if($default_server){?>
								<option value="<?=$server->id?>" <?=($default_server->id == $server->id) ? 'selected' : ''?>><?=$server->name?></option>
								<?php } else {?>
									<option value="<?=$server->id?>"><?=$server->name?></option>
								<?php }} ?>
							</select>
							</td>
							<td><?php if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts')) { ?><label class="switch">
									<input type="hidden" value="off" name="<?=$item->username?>_send_details[]" />
									<input type="checkbox" name="<?=$item->username?>_send_details[]">									
								<span></span>
							</label>
							<?php } ?></td>
						</tr>
						<?php } } ?>
					</tbody>
				</table>
				
				<h3><?=lang('domains')?></h3>
					<table class="table table-bordered table-striped">		
						<thead><tr><th><?=lang('service')?></th><th><?=lang('domain')?></th><th><?=lang('authcode')?></th><th><?=lang('nameservers')?></th><th><?=lang('register')?></th><th><?=lang('registrar')?></th></thead>
						<tbody>
								<?php foreach($order as $item) { 
									if($item->type == 'domain' || $item->type == 'domain_only') { ?>
									<tr><td><?=$item->item_name?></td>
									<td><?=$item->domain?></td>
									<td><input type="text" value="<?=$item->authcode?>" name="authcode[]" <?php if($item->item_name != lang('domain_transfer')) { ?> readonly <?php } ?>> </td>
									<td><?=$item->nameservers?></td>
									<td>
									<input type="hidden" name="domain_status[]" value="<?=$item->status_id?>">
									<input type="hidden" name="domain[]" value="<?=$item->id?>">
									<input type="hidden" name="domain_name[]" value="<?=$item->domain?>">
									<input type="hidden" name="domain_item_id[]" value="<?=$item->item_parent?>">
									<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts')) { ?>
									<label class="switch">
									<?php $domain = explode('.', $item->domain, 2); ?>
									<input type="hidden" value="off" name="<?=$domain[0]?>_activate" />
									<input type="checkbox" name="<?=$domain[0]?>_activate">
								<span></span>
							</label>
							<?php } ?> 
							</td>
							<td>
							<select name="registrar[]" class="form-control m-b">
							<?php
                                    
                                    $registrars = Plugin::domain_registrars();
                                    foreach ($registrars as $registrar)
                                    {?> 
									<option value="<?=$registrar->system_name;?>"><?=ucfirst($registrar->system_name);?></option>
                                    <?php } ?>
	
							</select></td>
							</tr>
							<?php } } ?>
						</tbody>
					</table>

				<?php } ?>
			</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-<?=config_item('theme_color');?>"><?=lang('activate')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
