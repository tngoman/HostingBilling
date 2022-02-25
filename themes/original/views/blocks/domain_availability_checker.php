<?php $domains = modules::run('domains/domain_pricing', ''); ?>
    <section class="domain_search">
          <div class="forms-main">
			   <div class="container">

                    <div id="response"></div>
                   <div id="continue"> <div class="btn-group"><a href="<?=base_url()?>cart/hosting_packages" class="btn btn-warning"><?=lang('add_hosting')?></a><a href="<?=base_url()?>cart/domain_only" class="btn btn-info"><?=lang('domain_only')?></a></div> </span></div>
           
				   <div class="grid grid-column-2">
						<div class="column">
							<h3><?=lang('check_domain_label')?></h3>							
						</div>	 
						<div class="column">
						    <form action="#" class="search_form" method="post" id="search_form">
                                <input name="domain" type="hidden" id="domain">
                                <input name="price" type="hidden" id="price">
                                <input name="type" type="hidden" id="type">
            
								<input id="searchBar" type="text" placeholder="<?=lang('enter_domain')?>">
								<span class="input-group-btn">

                                <select class="btn btn-default" name="ext" id="ext">
                                <?php foreach($domains as $domain) { ?>
                                <option value="<?=$domain->item_name;?>">.<?=$domain->item_name;?></option>                       
                                <?php } ?>    
                                </select>
                                
                                </span>
                                <button type="submit" id="Transfer" data="<?=lang('domain_transfer')?>"><?=lang('transfer')?></button>
                                <button type="submit" id="btnSearch" data="<?=lang('domain_registration')?>"><?=lang('check_domain')?></button>
                                <img id="checking" src="<?=base_url()?>resource/images/checking.gif"/>
                
							</form>
							<p>
                            <?php 
                            
                            $limit = 5;
                            $count = 0;
                            foreach($domains as $domain) { if($count == $limit){break;} ?>
                            .<?=$domain->item_name?><sub><?=Applib::format_currency(config_item('default_currency'), $domain->registration)?></sub>                      
                            <?php $count++; } ?>
                            </p>
						</div>
					</div>
			   </div>
		
		   </div>
    </section>
    