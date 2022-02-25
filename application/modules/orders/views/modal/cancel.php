<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('cancel_order')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'orders/cancel',$attributes); ?>
		<div class="modal-body">
		<input type="hidden" name="invoice_id" value="<?=$order[0]->invoice_id?>">
				<h3><?=lang('hosting')?></h3>
			
				<table class="table table-bordered table-striped">		
						<thead><tr><th><?=lang('service')?></th><th><?=lang('username')?></th><th><?=lang('password')?></th><th><?=lang('delete_controlpanel')?></th></thead>
						<tbody>
						<?php foreach($order as $item) { 
							if($item->type == 'hosting') { ?>
							<tr><td><?=$item->item_name?></td>
							<td>
							<input type="hidden" name="hosting[]" value="<?=$item->id?>">
							<input type="hidden" name="account[]" value="<?=$item->domain?>">
							<input type="hidden" name="service[]" value="<?=$item->item_name?>">
							<input type="text" value="<?=$item->username?>" name="username[]" class="form-control" readonly="readonly"></td>
							<td><input type="text" value="<?=$item->password?>" name="password[]" class="form-control" readonly="readonly">
							</td>
							<td>
							<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts')) { ?>
							<label class="switch">
									 <input type="hidden" value="off" name="<?=$item->username?>_delete_controlpanel" />
									<input type="checkbox" name="<?=$item->username?>_delete_controlpanel">									
								<span></span>
							</label>
							<?php } ?>
						</td></tr>
						<?php } } ?>
					</tbody>
				</table>
				
				<h3><?=lang('domains')?></h3>
			
				<table class="table table-bordered table-striped">		
						<thead><tr><th><?=lang('service')?></th><th><?=lang('domain')?></th><th><?=lang('nameservers')?></th></thead>
						<tbody>
						<?php foreach($order as $item) { 
							if($item->type == 'domain' || $item->type == 'domain_only') { ?>
							<tr><td><?=$item->item_name?></td><td><?=$item->domain?></td>
							<td>
							<input type="hidden" name="domain_name[]" value="<?=$item->domain?>">
							<input type="hidden" name="domain[]" value="<?=$item->id?>">
							<?=$item->nameservers?>
							</td>
							</tr>
						<?php } } ?>
					</tbody>
				</table>

				<div class="row"> 
					<div class="col-md-12">
					<span class="pull-right"><?=lang('credit_account')?>: <label class="switch">
						<input type="hidden" value="off" name="credit_account" />
						<input type="checkbox" name="credit_account">									
						<span></span>
						</label>
					</span>
				</div>
			</div>

		</div>
		

		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
		<button <?=(config_item('demo_mode') == 'TRUE') ? 'disabled' : ''?> type="submit" class="btn btn-<?=config_item('theme_color');?>"><?=lang('cancel_order')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
