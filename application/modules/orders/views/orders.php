<div id="order_list">

	<?php if($this->session->flashdata('message')): ?>
           <div class="alert alert-info" role="alert">
                <?php echo $this->session->flashdata('message') ?>
           </div>
        <?php endif ?>

		
	<div class="box">
	<div class="box-header"> 
	<div class="btn-group">
		<button class="btn btn-<?=config_item('theme_color');?> btn-sm">
		<?php
		$view = isset($_GET['view']) ? $_GET['view'] : NULL;
		switch ($view) {
		case 'unpaid':
			echo lang('unpaid');
			break;
		case 'paid':
			echo lang('paid');
			break;
		default:
			echo lang('filter');
			break;
		}
		?></button>
		<button class="btn btn-<?=config_item('theme_color');?> btn-sm dropdown-toggle" data-toggle="dropdown"><span class="caret"></span>
		</button>
		<ul class="dropdown-menu">

		<li><a href="<?=base_url()?>orders?view=unpaid"><?=lang('unpaid')?></a></li>
		<li><a href="<?=base_url()?>orders?view=paid"><?=lang('paid')?></a></li>
		<li><a href="<?=base_url()?>orders"><?=lang('all_orders')?></a></li>

		</ul>
		</div>

		<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'create_orders')) { ?>
			<a href="<?=base_url()?>orders/select_client" class="btn btn-sm btn-success pull-right"><i class="fa fa-plus"></i> <?=lang('new_order')?></a>
		</div>
		<?php } ?>
	
	<div class="box-body">
	<div class="table-responsive">
		<table class="table table-striped b-t b-light AppendDataTables">
			<thead>
				<tr>
					<th><?=lang('order_id')?></th>
					<th><?=lang('date')?></th>
					<th><?=lang('invoice')?></th> 
					<th><?=lang('status')?></th>
					<th><?=lang('invoice_options')?></th> 										
					<th><?=lang('client')?></th>	
					<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_orders')) { ?>				
					<th class="col-options no-sort w_200"><?=lang('action')?></th>
					<?php } ?>
				</tr> </thead> <tbody>
				<?php foreach ($orders as $key => $order) { 
					  $status = Invoice::payment_status($order->inv_id);
					  switch ($status) {
						  case 'fully_paid': $label2 = 'success';  break;
						  case 'partially_paid': $label2 = 'warning'; break;
						  case 'not_paid': $label2 = 'danger'; break;
						  case 'cancelled': $label2 = 'primary'; break;
					  }?>
				<tr>				
					<td><?=$order->order_id?></td>
					<td><?=$order->date?></td>					
					<td><?=$order->reference_no?></td>
					<td> <span class="label label-<?=$label2?>"><?=lang($status)?></span></td>
					<td>
					<a class="btn btn-xs btn-primary" href="<?=base_url()?>invoices/view/<?=$order->inv_id?>" 
                           data-toggle="tooltip" data-original-title="<?= lang('view') ?>" data-placement="top">
                           <i class="fa fa-eye"></i>
                           </a>  

					<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'email_invoices')) { ?>
							<a class="btn btn-xs btn-success" href="<?=base_url()?>invoices/send_invoice/<?=$order->inv_id?>" 
							data-toggle="ajaxModal"><span data-toggle="tooltip" data-original-title="<?=lang('email_invoice')?>" data-placement="top">
							<i class="fa fa-envelope"></i></span></a>
					<?php } ?>


					<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'send_email_reminders')) : ?>                   
						<a href="<?=base_url()?>invoices/remind/<?=$order->inv_id?>" data-toggle="ajaxModal" 
						class="btn btn-xs btn-vk" data-original-title="<?=lang('send_reminder')?>">
						<span data-toggle="tooltip" data-original-title="<?=lang('send_reminder')?>" data-placement="top">
						<i class="fa fa-bell"></i></span> </a>
					<?php endif; ?>
						
						<a class="btn btn-xs btn-linkedin" href="<?=base_url()?>fopdf/invoice/<?=$order->inv_id?>" 
						data-toggle="tooltip" data-original-title="<?=lang('pdf') ?>" data-placement="top">
						<i class="fa fa-file-pdf-o"></i></a>
				</td> 					
					<td><?=$order->company_name?></td>	
					<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_orders')) { ?>				
					<td>
				 			 
					<?php if ($order->status_id != 6 && $order->status_id != 2) { ?>
							<a href="<?=base_url()?>orders/activate/<?=$order->order_id?>" class="btn btn-xs btn-success" data-toggle="ajaxModal">
							<i class="fa fa-check"></i><?=lang('activate')?></a>
							<?php }?>
							<?php if ($order->status_id == 6) { ?>
							<a href="<?=base_url()?>orders/cancel/<?=$order->order_id?>" class="btn btn-xs btn-default" data-toggle="ajaxModal">
							<i class="fa fa-minus-circle"></i> <?=lang('cancel')?></a>
							<?php } ?>
							<a href="<?=base_url()?>orders/delete/<?=$order->order_id?>" class="btn btn-xs btn-default" data-toggle="ajaxModal">
							<i class="fa fa-trash-o"></i> <?=lang('delete')?></a> 
					</td>
					<?php } ?>
				</tr>
				<?php  } ?>
				
				
				
			</tbody>
		</table>
	</div>
	</div>
 </div>
</div>
 
 