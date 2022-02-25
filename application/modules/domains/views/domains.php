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
		default:
			echo lang('filter');
			break;
		}
		?></button>
		<button class="btn btn-<?=config_item('theme_color');?> btn-sm dropdown-toggle" data-toggle="dropdown"><span class="caret"></span>
		</button>
		<ul class="dropdown-menu">

		<li><a href="<?=base_url()?>domains?view=pending"><?=lang('pending')?></a></li>
		<li><a href="<?=base_url()?>domains?view=active"><?=lang('active')?></a></li>
		<li><a href="<?=base_url()?>domains?view=cancelled"><?=lang('cancelled')?></a></li>
		<li><a href="<?=base_url()?>domains"><?=lang('all_domains')?></a></li>

		</ul> 
		</div>

		<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts')) { ?>
		<a href="<?=base_url()?>domains/upload" class="btn btn-info btn-sm pull-right" title="<?=lang('domain')?>" data-placement="bottom"><i class="fa fa-download"></i> <?=lang('import_whmcs')?></a>
		<?php } ?>	

	</div>
	<div class="box-body">

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
                    <th><?=lang('invoice')?></th>
                    <th><?=lang('reference')?></th>                    
					<th><?=lang('status')?></th>
					<th><?=lang('nameservers')?></th>
					<?php if (User::is_admin() || User::is_staff() ) { ?>
					<th><?=lang('client')?></th>
					<?php } ?>
					<th class="col-options no-sort"><?=lang('action')?></th>
					</tr> </thead> <tbody>
					<?php 
				 
					 foreach($domains as $key => $order) {
						 $type = explode(" ", $order->item_name);	
						 $type = isset($type[1]) ? $type[1] : $order->item_name;		 
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
					<td><?=$type?></td>	
					<td><?=$order->domain?></td> 
					<td><a href="<?=base_url()?>invoices/view/<?=$order->inv_id?>"><?=$order->reference_no?></a></td>
                    <td><?=$order->status?></td>                    
					<td><span class="label <?=$label?>"><?=ucfirst($order->order_status)?></span></td>
					<td><?=$order->nameservers?></td>
	                <?php if (User::is_admin() || User::is_staff() ) { ?>
					<td><?=$order->company_name?></td>
					<?php } ?>
                    <td>
						<a href="<?= base_url()?>domains/domain/<?=$order->id?>" class="btn btn-<?=config_item('theme_color')?> btn-sm btn-block"><?=lang('details')?></a>
					</td> 
				</tr>
				<?php  } ?>
				
				
				
			</tbody>
			</table>
		</div>
	</div>
</div>
 