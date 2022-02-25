 <?php $client_cur = Client::get_by_user($this->session->userdata('user_id'))->currency; ?>
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?=lang('upgrade_account')?></h4>
      </div>
      <div class="modal-body">
 		<div class="row">
		 <div class="col-md-12" id="change">

		 <?php 
				$count = 0; 
				
				
                foreach($packages as $plan) { ?>

                    <?php

                    $price = 0;
                    $period = '';
                    $count++;
                    
                    if($plan->annually > 0) {
                        $price = $plan->annually;
                        $period = ' / year';
                    }

                    if($plan->semi_annually > 0) {
                        $price = $plan->semi_annually;
                        $period = ' / semi-annually';
                    }

                    if($plan->quarterly > 0) {
                        $price = $plan->quarterly;
                        $period = ' / quarterly';
                    }

                    if($plan->monthly > 0) {
                        $price = $plan->monthly;
                        $period = ' / monthly';
                    }
                    
                    $features = explode(",", $plan->item_features);
                    
                    ?>
                    <div class="columns">
                        <ul class="price">
                            <li class="<?= ($count == 2) ? 'highlight' : 'header' ?>"><?=ucfirst($plan->item_name)?></li>
                            <li class="grey">
                            <?php if(!User::is_admin() && !User::is_staff()) {                                         
                                           echo Applib::format_currency($client_cur, Applib::client_currency($client_cur, $price));
                                        }
                                        else{
                                            echo Applib::format_currency(config_item('default_currency'), $price);
                                        } 
                               ?>
                              <?=$period?> </li>                           
                            
                            <?php

                            if(count($features) > 0) { foreach($features as $feature ) { ?>
                            <li><?=$feature?></li>
                            <?php } } ?> 
                            <li class="grey"><a href="<?=base_url()?>accounts/show_options/<?=$plan->item_id?>" class="btn btn-success">Select</a></li>
                        </ul>
                    </div>

					<?php } ?>   
				 
				</div>
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
 