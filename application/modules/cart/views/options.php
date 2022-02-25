<?php
   $package = $package[0];
   if(isset($_GET['item'])) { 
	$options = array('total_cost', 'monthly', 'quarterly', 'semi_annually', 'annually');
   }

   else {
	   header('Location: '.base_url());
   }
?>

<div class="box"> 	
	<div class="container inner">
        <div class="row"> 
					<div class="col-md-6">
					<h2><?=$package->item_name?></h2>	
						<?php
						$attributes = array('class' => 'bs-example form-horizontal');
						echo form_open(base_url().'cart/options',$attributes); ?> 
						<input type="hidden" name="id" value="<?=$package->item_id?>">
							<div class="row">
								<div class="col-md-8">
									<select class="form-control" name="selected">
									<?php 

									$count = 0;									
									foreach ($options as $key => $value) {       
										if(isset($package->$value) && $package->$value > 0) { $count++; ?>
											<option value="<?=$package->item_id?>,<?=$package->item_name?>,<?=$value?>,<?=$package->$value?>"><?=Applib::format_currency(config_item('default_currency'), $package->$value)?> - <?= lang($value) ?></option>
									<?php } }
									
									if($count == 0) {
										foreach ($options as $key => $value) {
										if(isset($package->$value) && $package->$value == 0) { ?>
											<option value="<?=$package->item_id?>,<?=$package->item_name?>,<?=$value?>,<?=$package->$value?>"><?=Applib::format_currency(config_item('default_currency'), $package->$value)?> - <?= lang($value) ?></option>
									<?php if($package->$value == 0) break;  
											}
										}
									}
									?>
									</select>
								</div>
								<div class="col-md-4">
									<input type="submit" class="btn btn-success btn-block" value="<?= lang('continue') ?>">
								</div>
							</div>
						</form>
						</div>
					</div>
				</div>
				<div class="h_100"></div>
	 </div>
