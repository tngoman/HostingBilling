<?php 
	$options = intervals();
	$options['total_cost'] = 0;
    $item = Item::view_item($id);
?>

<section id="pricing" class="bg-silver-light">
    <div class="container"> 
                <div class="col-md-6 col-md-offset-2">
                <div class="box box-solid box-default">   
                    
                <div class="box-header">

                <h2 class="title text-uppercase text-theme-color-2 line-bottom-double-line-centered">Addons</h2>
                    <p class="font-13 mt-10">The addons below may be added to the selected package</p>
</div>
        <div class="box-body">
            
                    <h2><?=$item->item_name . " " . lang('addons')?></h2>
                    <hr>
                    <?php

                        $attributes = array('class' => 'bs-example form-horizontal');
                        echo form_open(base_url().'cart/options',$attributes); 

                        foreach($addons as $k => $package)
                        { ?>

                    <div class="row">
                        <div class="col-md-6">
                            <h5><?=$package->item_name?>
                        </div>

                        <div class="col-md-6">
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
                                    <?=Applib::format_currency(config_item('default_currency'), $package->$key)?> -
                                    <?= lang($key) ?></option>
                                <?php //if($package->$value == 0) break;
                                                }  
                                            }
                                        
                                        ?>
                            </select>
                        </div>
                    </div>
                    <hr>

                    <?php } ?>

                    <input type="submit" class="btn btn-success btn-block" value="<?= lang('continue') ?>">

                    </form>
                </div>
            </div>
        </div>
     
    </div>
</section>