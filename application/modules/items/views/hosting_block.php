<div class="row pricing-row" id="style-<?=$style?>">          
        <?php 
        $count = 0; 
        foreach($items as $plan) {  

            $price = 0;
            $period = '';
            $count++;
            
            if($plan->annually > 0) :
                $price = $plan->annually;
                $period = lang('annually');
            endif;

            if($plan->semi_annually > 0) :
                $price = $plan->semi_annually;
                $period = lang('semi_annually');
            endif;

            if($plan->quarterly > 0) :
                $price = $plan->quarterly;
                $period = lang('quarterly');
            endif;

            if($plan->monthly > 0) :
                $price = $plan->monthly;
                $period = lang('monthly');
            endif;
            
            $features = explode(",", $plan->item_features);
            $price = explode('.', $price);
            ?>

        <div class="col-lg-4 col-md-6 pricing-col">
                <div class="pricing pricing-<?=$style?> <?= ($count == 3 || $count == 5) ? 'starter' : 'premium' ?>">
                    <div class="bg-element"></div>
                    <p class="pricing-title"><?=ucfirst($plan->item_name)?></p>
                    <div class="price">
                        <div class="currency"><?=App::currencies(config_item('default_currency'))->symbol?></div>
                        <div class="num"><?=$price[0]?></div>
                        <?php if($style == 'one' || $style == 'two' || $style == 'three' || $style == 'one') { ?>
                        <div class="period"><?=$period?></div>
                        <?php } ?>
                    </div>
                    <?php if($style != 'one' && $style != 'two' && $style != 'three' && $style != 'one') { ?>
                        <div class="period"><?=($style != 'three') ? '/': ''?><?=$period?></div>
                    <?php } ?>                    

                    <ul class="specs">                        
                        <?php 
                            if(count($features) > 0) { foreach($features as $feature ) { ?>
                            <li>
                                <?=$feature?>
                            </li>
                        <?php } } ?>                        
                    </ul> 

                    <?php if($style == 'two') { ?>
                        <div class="bg-element-2"></div>
                    <div class="button-holder"> 
                    <?php } ?>
                                            
                        <a href="<?=base_url()."cart/options?item=".$plan->item_id?>" class="btn"><?=lang('order_now')?></a>
                        <?php if($style == 'two') { ?>
                        </div>
                    <?php } ?>
                
                </div>
            </div> 
        <?php } ?>            
</div>
 
