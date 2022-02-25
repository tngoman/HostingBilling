 <div class="box">
	<div class="box-header"> 			
			<span class="h3">&nbsp;  
			<?php echo lang('addons'); ?>
			</span>
   
			<a href="<?=base_url()?>addons/add" class="btn btn-sm btn-<?=config_item('theme_color');?> pull-right" data-toggle="ajaxModal"><i class="fa fa-plus"></i> <?=lang('new_addon')?></a>
 
	</div>
	 
	<div class="box-body">
	<div class="table-responsive">
		<table id="table-templates-2" class="table table-striped b-t b-light text-sm AppendDataTables">
			<thead>
				<tr>	
					<th><?=lang('item_name')?></th>					 
					<th><?=lang('add_to')?></th>
					<th><?=lang('require_domain')?></th>
					<th><?=lang('monthly')?></th>
					<th><?=lang('quarterly')?></th>
					<th><?=lang('semiannually')?></th>
					<th><?=lang('annually')?></th> 
					<th><?=lang('biennially')?></th>  
					<th><?=lang('triennially')?></th> 
					<th class="col-options no-sort"><?=lang('options')?></th>
				</tr> </thead> <tbody>
				<?php foreach ($addons as $key => $item) { ?>
				<tr> 
				<td><?=$item->item_name?></td>
				<td>
				
				<?php 
					$packages = unserialize($item->apply_to);
					if(is_array($packages))
					{
						foreach($packages as $package)
						{
							if(isset(Item::view_item($package)->item_name))
							{
								echo '<span class="label label-default">'.Item::view_item($package)->item_name.'</span>';
							}						
						}
					}					
				?>

				</td>
				<td><?=$item->require_domain?></td>
				<td><?=Applib::format_currency(config_item('default_currency'), $item->monthly)?></td>
				<td><?=Applib::format_currency(config_item('default_currency'), $item->quarterly)?></td>
				<td><?=Applib::format_currency(config_item('default_currency'), $item->semi_annually)?></td>
				<td><?=Applib::format_currency(config_item('default_currency'), $item->annually)?></td>
				<td><?=Applib::format_currency(config_item('default_currency'), $item->biennially)?></td>  
				<td><?=Applib::format_currency(config_item('default_currency'), $item->triennially)?></td> 
				<td><a href="<?=base_url()?>items/edit_addon/<?=$item->item_id?>" class="btn btn-primary btn-xs" data-toggle="ajaxModal">
					<i class="fa fa-edit"></i> <?=lang('edit')?></a>
					<a href="<?=base_url()?>items/package/<?=$item->item_id?>" class="btn btn-warning btn-xs">
					<i class="fa fa-edit"></i> <?=lang('package')?></a>
					<a href="<?=base_url()?>items/delete_addon/<?=$item->item_id?>" class="btn btn-danger btn-xs" data-toggle="ajaxModal">
					<i class="fa fa-trash-o"></i> <?=lang('delete')?></a>
				</td>
				</tr>
				<?php } ?>							
				
			</tbody>
		</table>
	</div>


</div>
<!-- End Invoice Items -->
