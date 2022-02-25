<?php if($this->session->flashdata('message')): ?>
           <div class="alert alert-info" role="alert">
                <?php echo $this->session->flashdata('message') ?>
           </div>
        <?php endif ?>

	<div class="table-responsive">
    <table id="table-templates-2" class="table table-striped b-t b-light text-sm AppendDataTables">
			<thead>
				<tr>
					<th><?=lang('type')?></th>
					<th><?=lang('domain')?></th>                      
					<th><?=lang('status')?></th>
					<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts')) { ?>				
					<th><?=lang('action')?></th>
					<th><?=lang('options')?></th>
					<?php } ?>
				</tr> </thead> <tbody>
					<?php foreach(Domain::by_client($company, "(type ='domain' OR type ='domain_only')") AS $order) { 
						$type = explode(" ", $order->item_name)[1]; ?>
		 		    <tr>	
					<td><?=$type?></td>	
					<td><?=$order->domain?></td>              
					<td><?=ucfirst($order->order_status)?></td>
					<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts')) { ?>
                    <td>
						<?php if ($order->status_id != 6) { ?>
						<a href="<?=base_url()?>domains/activate/<?=$order->id?>" class="btn btn-sm btn-success" data-toggle="ajaxModal">
						<i class="fa fa-check"></i><?=lang('activate')?></a>
						<?php } else { ?>
						<a href="#" class="btn btn-sm btn-white">
						<i class="fa fa-check"></i><?=lang('activate')?></a>
						<?php } ?>
						<a href="<?=base_url()?>domains/cancel/<?=$order->id?>" class="btn btn-sm btn-default" data-toggle="ajaxModal">
						<i class="fa fa-minus-circle"></i> <?=lang('cancel')?></a>
						<a href="<?=base_url()?>domains/delete/<?=$order->id?>" class="btn btn-sm btn-danger" data-toggle="ajaxModal">
						<i class="fa fa-trash-o"></i> <?=lang('delete')?></a>
					</td>			
					<td>
                      <a href="<?=base_url()?>domains/domain/<?=$order->id?>" class="btn btn-sm btn-default"><?=lang('view')?> </a>
                      <a href="<?=base_url()?>domains/manage/<?=$order->id?>" class="btn btn-sm btn-default"><?=lang('manage')?> </a>
					</td>					
					<?php } ?>
				</tr>
				<?php  } ?>
				
				
				
			</tbody>
		</table>
	  </div>
