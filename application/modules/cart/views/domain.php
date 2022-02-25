<?php 
    $cart = $this->session->userdata('cart'); 
    $total = 0;
?>

<div class="box">
    <div class="container inner domain_search">
        <div class="row">
                    <div class="col-sm-3">
                        <a href="<?=base_url()?>cart/add_existing"><button type="submit" id="Existing"><?=lang('use_existing_domain')?></button></a>
                    </div>
     
                    <div class="col-sm-8">
                            <form action="<?=base_url()?>cart/add_domain" class="search_form" method="post" id="search_form cart">
                                <input name="domain" type="hidden" id="domain">
                                <input name="price" type="hidden" id="price">
                                <input name="type" type="hidden" id="type">
            
                                <input id="searchBar" type="text" placeholder="Enter your domain name...">
                                <span class="input-group-btn">

                                <select class="btn btn-default" name="ext" id="ext">
                                <?php foreach($domains as $domain) { ?>
                                <option value="<?=$domain->item_name;?>">.<?=$domain->item_name;?></option>                       
                                <?php } ?>    
                                </select>
                                </span> 
                                <button type="submit" id="Transfer" data="<?=lang('domain_transfer')?>"><?=lang('transfer')?></button>
                                <button type="submit" id="btnSearch" data="<?=lang('domain_registration')?>"><?=lang('register')?></button>
                                
                                <img id="checking" src="<?=base_url()?>resource/images/checking.gif"/>                
                            </form>	 
                    </div>
                   
                </div>

                <div id="response"></div>
             
    </div>
</div>


 