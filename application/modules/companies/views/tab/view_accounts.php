   <?php if($this->session->flashdata('message')): ?>
           <div class="alert alert-info" role="alert">
                <?php echo $this->session->flashdata('message') ?>
           </div>
     <?php endif ?>

    <div class="table-responsive">
    <table id="table-templates-2" class="table table-striped b-t b-light text-sm AppendDataTables">
			<thead>
				<tr>
                    <th><?=lang('package')?></th>
                    <th><?=lang('invoice')?></th>
                    <th><?=lang('status')?></th>
                    <th><?=lang('domain')?></th>
					<th><?=lang('service')?></th>
                    <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts')) { ?>			
					<th class="col-options no-sort"><?=lang('action')?></th>
                    <?php } ?>
				    </tr>
                    </thead> <tbody>
				<?php foreach(Domain::by_client($company, "(type ='hosting')") AS $order) {  ?>
				    <tr>	
                    <td><?=$order->item_name?></td>	
                    <td><?=$order->reference_no?></td>
                    <td><?=$order->status?></td>
                    <td><?=$order->domain?></td>
					<td><?=ucfirst($order->order_status)?></td>
	                 <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts')) { ?>
                    <td>
                      <a href="<?=base_url()?>accounts/account/<?=$order->id?>" class="btn btn-sm btn-success"><?=lang('view')?> </a>
                      <a href="<?=base_url()?>accounts/manage/<?=$order->id?>" class="btn btn-sm btn-warning"><?=lang('manage')?> </a>
					</td>
                  <?php } ?>
				</tr>
				<?php  } ?>
				
				
				
			</tbody>
		</table>
      </div>
 