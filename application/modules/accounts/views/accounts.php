<div class="box">
	<div class="box-header"> 
		<div class="btn-group">
		<button class="btn btn-<?=config_item('theme_color');?> btn-sm">
		<?php
		$view = isset($_GET['view']) ? $_GET['view'] : NULL;
		switch ($view) {
		case 'pending':
			echo lang('pending');
			break;

		case 'active':
			echo lang('active');
			break;

		case 'cancelled':
			echo lang('cancelled');
			break;

		case 'suspended':
			echo lang('suspended');
			break;

		default:
			echo lang('filter');
			break;
		}
		?></button>
		<button class="btn btn-<?=config_item('theme_color');?> btn-sm dropdown-toggle" data-toggle="dropdown"><span class="caret"></span>
		</button>
		<ul class="dropdown-menu">

		<li><a href="<?=base_url()?>accounts?view=pending"><?=lang('pending')?></a></li>
		<li><a href="<?=base_url()?>accounts?view=active"><?=lang('active')?></a></li>
		<li><a href="<?=base_url()?>accounts?view=suspended"><?=lang('suspended')?></a></li>
		<li><a href="<?=base_url()?>accounts?view=cancelled"><?=lang('cancelled')?></a></li>
		<li><a href="<?=base_url()?>accounts"><?=lang('all_accounts')?></a></li>

		</ul>
		</div>

		<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts')) { ?>

		<a href="<?=base_url()?>accounts/upload" class="btn btn-info btn-sm pull-right" title="<?=lang('domain')?>" data-placement="bottom"><i class="fa fa-download"></i> <?=lang('import_whmcs')?></a>
	
		<?php } ?>
	</div>
	<div class="box-body" id="box">
 
	<div class="table-responsive">
		<table id="table-templates-2" class="table table-striped b-t b-light text-sm AppendDataTables">
			<thead>
				<tr>
                    <th><?=lang('package')?></th> 
                    <th><?=lang('status')?></th>
                    <th><?=lang('domain')?></th>
					<th><?=lang('service')?></th>
					<?php if (User::is_admin() || User::is_staff() ) { ?> 
					<th><?=lang('client')?></th>	
					<?php } ?>
					<th><?=lang('control_panel')?></th>
					<th><?=lang('server')?></th>
					<th class="col-options no-sort"><?=lang('options')?></th> 
				</tr> 
			</thead> 
			<tbody>
				<?php 

				if(config_item('demo_mode') == 'TRUE') {
					$accounts = array_reverse($accounts);
				}		

				foreach ($accounts as $key => $order) { 
					switch($order->order_status) {
						case 'pending' : $label = 'label-warning';
						break;

						case 'active' : $label = 'label-success';
						break;

						case 'suspended' : $label = 'label-danger';
						break;

						default : $label = 'label-default';
						break;

							}
                        ?>
				    <tr>	
                    <td><?=$order->item_name?></td>	
                    <td><?=$order->status?></td>
                    <td><?=$order->domain?></td>
					<td><span class="label <?=$label?>"><?=ucfirst($order->order_status)?></span></td>
					<?php if (User::is_admin() || User::is_staff() ) { ?>			 			
                    <td><?=$order->company_name?></td>
					<?php } ?>
					<td><?=ucfirst($order->type)?></td>    
					<td><?=$order->server_name?></td>                
					<td><a href="<?= base_url()?>accounts/account/<?=$order->id?>" class="btn btn-<?=config_item('theme_color')?> btn-sm btn-block"><?=lang('options')?></a></td> 				
				</tr>
				<?php  } ?> 
				</tbody>
			</table>
		</div>
	</div>
</div>