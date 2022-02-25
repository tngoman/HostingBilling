<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('delete_order')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'domains/delete',$attributes); ?>
		<div class="modal-body">
		<input type="hidden" name="id" value="<?=$item->id?>">
		<input type="hidden" name="domain" value="<?=$item->domain?>">
		<input type="hidden" name="order" value="<?=$item->type?>">
		<input type="hidden" name="inv_id" value="<?=$item->invoice_id?>">
			
		<h3><?=$item->domain?></h3>
					<table class="table table-bordered table-striped">		
						<thead><tr><th><?=lang('service')?></th><th><?=lang('nameservers')?></th><th><?=lang('delete')?></th></thead>
						<tbody> 
							<tr><td><?=$item->item_name?></td>
							<td><?=$item->nameservers?></td>
							<td>							
							<label class="switch">
									<input type="hidden" value="off" name="delete_domain" />
									<input type="checkbox" <?php if($item->status_id == 6){ echo "checked=\"checked\""; } ?> name="delete_domain">
								<span></span>
							</label></td></tr>				 
					</tbody>
				</table>
				<div class="row"> 
				<div class="col-md-12">
					<span class="pull-right"><?=lang('credit_account_item')?>: <label class="switch">
						<input type="hidden" value="off" name="credit_account" />
						<input type="checkbox" name="credit_account">									
						<span></span>
						</label>
					</span>
				</div>
				</div>	
				 
		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
		<button <?=(config_item('demo_mode') == 'TRUE') ? 'disabled' : ''?> type="submit" class="btn btn-<?=config_item('theme_color');?>"><?=lang('delete_domain')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
