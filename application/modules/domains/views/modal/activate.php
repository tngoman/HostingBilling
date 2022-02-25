<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('activate_order')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'domains/activate',$attributes); ?>
		<div class="modal-body">
		<div class="row">
		 
					<table class="table table-bordered table-striped">		
						<thead><tr><th><?=lang('service')?></th><?php if($item->item_name == lang('domain_transfer')) { ?><th><?=lang('authcode')?></th><?php } ?><th><?=lang('nameservers')?></th><th><?=lang('register_transfer')?></th><th><?=lang('registrar')?></th></thead>
						<tbody> 
							<tr><td><?=$item->item_name?></td>
							<?php if($item->item_name == lang('domain_transfer')) { ?><td><input type="text" value="<?=$item->authcode?>" name="authcode"></td> <?php } ?>
							<td><?=$item->nameservers?></td>
							<td>
							<input type="hidden" name="domain_status" value="<?=$item->status_id?>">
							<input type="hidden" name="id" value="<?=$item->id?>">
							<input type="hidden" name="domain" value="<?=$item->item_desc?>">
							<label class="switch">
									<input type="hidden" value="off" name="activate_domain" />
									<input type="checkbox" <?php if($item->status_id == 6){ echo "checked=\"checked\""; } ?> name="activate_domain">
								<span></span>
							</label></td>
							<td>
							<select name="registrar" class="form-control m-b">
							<option value=""><?=lang('registrar')?></option>
							<?php 
							
							if(config_item('resellerclub_live') == 'TRUE') { ?>
							<option value="resellerclub">ResellerClub</option>
							<?php }

							if(config_item('domainscoza_live') == 'TRUE') { ?>
								<option value="domainscoza">DomainsCO.ZA</option>
							<?php }


							if(config_item('namecheap_live') == 'TRUE') { ?>
								<option value="namecheap">Namecheap</option>
								<?php }
							
							?>
						</select></td></tr>				 
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
