 <div class="box">
	<div class="box-header"> 			
			<span class="h3">&nbsp;  
			<?php
				$view = isset($_GET['view']) ? $_GET['view'] : NULL;
				$path = '';
				switch ($view) {
				case 'domains':
					$path = 'domain';
					echo lang('domains');
					break;
				case 'hosting':
					$path = 'hosting';
					echo lang('hosting');
					break;
				case 'service': 
					$path = 'service';
					echo lang('service');
					break; 
				}

				if(!isset($_GET['view'])) {?> 
					<a class="btn btn-default btn-sm btn-responsive" href="<?=base_url()?>items"><?=lang('all_items')?></a>
					<a class="btn btn-twitter btn-sm btn-responsive" href="<?=base_url()?>items?view=domains"><?=lang('domains')?></a>
					<a class="btn btn-success btn-sm btn-responsive" href="<?=base_url()?>items?view=hosting"><?=lang('hosting')?></a>
					<a class="btn btn-warning btn-sm btn-responsive" href="<?=base_url()?>items?view=service"><?=lang('service')?></a>
				<?php }	?>
			</span>
   		<?php if(isset($_GET['view'])) {?> 
			<a href="<?=base_url()?>items/add_<?=$path?>" class="btn btn-sm btn-<?=config_item('theme_color');?> pull-right" data-toggle="ajaxModal"><i class="fa fa-plus"></i> <?=lang('new_item')?></a>
		<?php }	?>
	</div>
	 
	<div class="box-body">
	<div class="table-responsive">
		<table id="table-templates-2" class="table table-striped b-t b-light text-sm AppendDataTables">
			<thead>
				<tr>
					<th><?=lang('category')?></th>					
					<th><?=lang('item_name')?></th>					
					<?php if((isset($_GET['view']) && $_GET['view'] == 'hosting') || !isset($_GET['view'])) { ?>
					<th><?=lang('server')?></th>
					<th><?=lang('monthly')?></th>
					<th><?=lang('quarterly')?></th>
					<th><?=lang('semiannually')?></th>
					<th><?=lang('annually')?></th>
					<?php }					
					if((isset($_GET['view']) && $_GET['view'] == 'domains') || !isset($_GET['view'])) { ?>
					<th><?=lang('registration')?></th>
					<th><?=lang('transfer')?></th>
					<th><?=lang('renewal')?></th>	
					<?php } 
					if((isset($_GET['view']) && $_GET['view'] == 'service') || !isset($_GET['view'])) { ?>
					<th><?=lang('server')?></th>
					<th><?=lang('unit_price')?> </th> 
					<?php } ?>
					<th><?=lang('order')?> </th>
					<th class="col-options no-sort"><?=lang('options')?></th>
				</tr> </thead> <tbody>
				<?php foreach ($invoice_items as $key => $item) { ?>
				<tr>
				<td><span class="label label-default"><?=($item->cat_name == '') ? 'None' : $item->cat_name?></span></td>
				<td><?=$item->item_name?></td>								
				<?php if((isset($_GET['view']) && $_GET['view'] == 'hosting') || !isset($_GET['view'])) { ?>
				<td><?=($item->server != '') ? '<span class="label label-default">'.$item->server.'</span>' : '' ?></td>
				<td><?=Applib::format_currency(config_item('default_currency'), $item->monthly)?></td>
				<td><?=Applib::format_currency(config_item('default_currency'), $item->quarterly)?></td>
				<td><?=Applib::format_currency(config_item('default_currency'), $item->semi_annually)?></td>
				<td><?=Applib::format_currency(config_item('default_currency'), $item->annually)?></td>
				<?php } 
				if((isset($_GET['view']) && $_GET['view'] == 'domains') || !isset($_GET['view'])) { ?>
				<td><?=Applib::format_currency(config_item('default_currency'), $item->registration)?></td>
				<td><?=Applib::format_currency(config_item('default_currency'), $item->transfer)?></td>
				<td><?=Applib::format_currency(config_item('default_currency'), $item->renewal)?></td>
				<?php } 
				if((isset($_GET['view']) && $_GET['view'] == 'service') || !isset($_GET['view'])) { ?>
				<td><?=($item->server != '') ? '<span class="label label-default">'.$item->server.'</span>' : '' ?></td>
				<td><?=Applib::format_currency(config_item('default_currency'), $item->unit_cost)?></td>	
				<?php } ?> 
				<td><?=$item->order_by?></td>               
				<td><a href="<?=base_url()?>items/edit_<?=$path?>/<?=$item->item_id?>" class="btn btn-primary btn-xs" data-toggle="ajaxModal">
					<i class="fa fa-edit"></i> <?=lang('edit')?></a>
					<?php if($path == 'hosting' || $path == 'service') { ?>
					<a href="<?=base_url()?>items/package/<?=$item->item_id?>" class="btn btn-warning btn-xs">
					<i class="fa fa-edit"></i> <?=lang('package')?></a>
					<?php } if($_GET['view'] != 'domains') { ?>
					<a href="<?=base_url()?>items/item_links/<?=$item->item_id?>" class="btn btn-info btn-xs" data-toggle="ajaxModal">
					<i class="fa fa-link"></i> <?=lang('links')?></a> 
					<?php if(config_item('affiliates') == 'TRUE') { ?>
					<a href="<?=base_url()?>items/affiliates/<?=$item->item_id?>" class="btn btn-info btn-xs" data-toggle="ajaxModal">
					<i class="fa fa-link"></i> <?=lang('affiliates')?></a> <?php }} ?>
					<a href="<?=base_url()?>items/delete_<?=$path?>/<?=$item->item_id?>" class="btn btn-danger btn-xs" data-toggle="ajaxModal">
					<i class="fa fa-trash-o"></i> <?=lang('delete')?></a>
				</td>
				</tr>
				<?php  } ?>
				
				
				
			</tbody>
		</table>
	</div>


</div>
<!-- End Invoice Items -->
