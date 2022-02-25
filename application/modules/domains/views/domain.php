<?php $order = Order::get_domain_order($id); ?> 
<div class="box">
	<div class="box-body">	
	<?php if($this->session->flashdata('message')): ?>
           <div class="alert alert-info alert-dismissible">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?php echo $this->session->flashdata('message') ?>
           </div>
		<?php endif ?>
						
				<div class="box box-solid">
					<div class="box-header with-border">
						<h2 class="text-muted"><?=$order->domain?></h2>	 
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-lg-5">
								<table class="table table-padded table-bordered">
									<tr><td><label><?=lang('order_date')?></label></td><td><?=substr($order->date, 0, 10)?></td></tr>
									<tr><td><label><?=lang('renewal')?></label></td><td><?=$order->renewal_date?></td></tr>
									<?php if($order->registrar != '') { ?>
									<tr><td><label><?=lang('registrar')?></label></td><td><?=ucfirst($order->registrar)?></td></tr>										
									<?php } ?>
									<tr><td><label><?=lang('status')?></label></td><td><span class="label bg-info"><?=ucfirst($order->domain_status)?></span></td></tr>
									<?php if($order->authcode != '') { ?>
									<tr><td><label><?=lang('authcode')?></label></td><td><?=$order->authcode?></td></tr>										
									<?php } ?>
								</table>
								

								<h3><?=lang('nameservers')?></h3>
								<ul class="list-group alt">
										<?php 
											if($order->nameservers != '') {
												$nameservers = explode(",", $order->nameservers);
											}

										else {

												$nameservers = array();
												if(config_item('nameserver_one') != '') {
													$nameservers[] = config_item('nameserver_one');
												}
												if(config_item('nameserver_two') != '') {
													$nameservers[] = config_item('nameserver_two');
												}
												if(config_item('nameserver_three') != '') {
													$nameservers[] = config_item('nameserver_three');
												}
												if(config_item('nameserver_four') != '') {
													$nameservers[] = config_item('nameserver_four');
												}
												if(config_item('nameserver_five') != '') {
													$nameservers[] = config_item('nameserver_five');
												}													
											}


											foreach($nameservers AS $server => $value) { ?>
												<li class="list-group-item text-muted"><?=$value?></li>
									<?php } ?>
								</ul>

								</div>
							
									<div class="col-lg-2">
										<?php if($order->status_id > 5 && $order->registrar != '' && (User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts'))) 
										{ 

											$domain = array('name' => $order->domain , 'id' => $order->id, 'status_id' => $order->status_id);
											echo modules::run($order->registrar.'/domain_options', $domain);										  

								   		} ?>
										   <a href="<?=base_url()?>domains/manage_nameservers/<?=$order->id?>" class="btn btn-sm btn-primary btn-block" data-toggle="ajaxModal">
											<?=lang('nameservers')?></a>
									</div>
									<div class="col-lg-4">
										<?=($order->notes != '') ? $order->notes : ''?>

									</div>
							
							</div>
						</div>
				
					<div class="box-footer">	
						<?php if(User::is_admin() ||  User::perm_allowed(User::get_id(),'manage_accounts')){ ?>
							<?php if ($order->status_id != 6) { ?>
							<a href="<?=base_url()?>domains/activate/<?=$order->id?>" class="btn btn-sm btn-success" data-toggle="ajaxModal">
							<i class="fa fa-check"></i><?=lang('activate')?></a>
							<?php } else { ?>
							<a href="#" class="btn btn-xs btn-white">
							<i class="fa fa-check"></i><?=lang('activate')?></a>

							<a href="<?=base_url()?>domains/manage/<?=$id?>"  
								class="btn btn-sm btn-primary">
							<i class="fa fa-edit"></i> <?=lang('manage')?></a>

							<?php } ?>
							<a href="<?=base_url()?>domains/cancel/<?=$order->id?>" class="btn btn-sm btn-default" data-toggle="ajaxModal">
							<i class="fa fa-minus-circle"></i> <?=lang('cancel')?></a>
							<a href="<?=base_url()?>domains/delete/<?=$order->id?>" class="btn btn-sm btn-danger" data-toggle="ajaxModal">
							<i class="fa fa-trash-o"></i> <?=lang('delete')?></a>
						<?php } ?>
					</div>
				</div>
			
	</div>
</div>