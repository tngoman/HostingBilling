<?php

   $package = $package[0];
   if(isset($_GET['item'])) { 
	$options = intervals();
	$options['total_cost'] = 0;
   }

   else {
	   header('Location: '.base_url());
   }
?>
<div class="container inner">
    <div class="box box-solid box-default">    
        <div class="box-body">
            <div class="row">
                <div class="col-sm-6">

                    <section id="pricing" class="bg-silver-light">
                        <div class="container">
                            <div class="section-title text-center mb-40">
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2">
                                        <h2
                                            class="title text-uppercase text-theme-color-2 line-bottom-double-line-centered">
                                        </h2>
                                        <p class="font-13 mt-10"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="section-content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2><?=$package->item_name?></h2>
                                        <?php
						$attributes = array('class' => 'bs-example form-horizontal');
						echo form_open(base_url().'cart/options',$attributes); ?>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <select class="form-control" name="selected[]">
                                                    <?php 

									$interval = false;

									foreach ($options as $key => $value) {       
										if(isset($package->$key) && $package->$key > 0) { 
												$interval = true; 
											}  
										} 

									foreach ($options as $key => $value) {       
										if(isset($package->$key) && $package->$key > 0 || $interval == false && $key == 'total_cost') { ?>
                                                    <option
                                                        value="<?=$package->item_id?>,<?=$package->item_name?>,<?=$key?>,<?=$package->$key?>">
                                                        <?=Applib::format_currency(config_item('default_currency'), $package->$key)?>
                                                        - <?= lang($key) ?></option>
                                                    <?php //if($package->$value == 0) break;
									      }  
										}
									
									?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="submit" class="btn btn-success btn-block"
                                                    value="<?= lang('continue') ?>">
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>

            </div>
        </div>
        </section>


    </div>
</div>
</div>
</div>
</div>