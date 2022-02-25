<?php 
    $cart = $this->session->userdata('cart'); 
    $total = 0;
    $tax = false; 
    $tax_total = 0;   
    foreach($cart as $row) {  
        if(floatval($row->tax) > 0)
        {
            $tax_total += $row->tax;
            $tax = true;
        }
    }

    //print_r($cart); die;
?>
<div class="box"> 	
<div class="container inner">
        <div class="row">  
 
        <div class="col-sm-10"> 
      
        <table class="table table-bordered shopping_cart" id="shopping_cart">
            <thead>
                <tr>
                    <th><?=lang('item')?></th>
                    <th><?=lang('domain')?></th>
                    <th><?=lang('billed')?></th>
                    <th><?=lang('amount')?></th>
                    <?php if($tax){ ?>
                    <th><?=lang('tax')?></th>
                    <?php } ?>
                    <th class="w_100"><?=lang('action')?></th>
                </tr>
            </thead>
            <tbody id="domains">
                
                <?php 
                foreach($cart as $row) {  
                    $total += $row->price; 
                ?>
            <tr>
                <td><?=$row->name?></td>
                <td><?=$row->domain?></td>
                <td><?=lang($row->renewal)?></td>
                <td><?=Applib::format_currency(config_item('default_currency'), $row->price)?></td>
                <?php if($tax){ ?>
                    <td><?=Applib::format_currency(config_item('default_currency'), $row->tax)?></td>
                    <?php } ?>
                <td><a class="btn btn-block btn-sm btn-default" href="<?=base_url()?>cart/remove/<?=$row->cart_id?>"><?=lang('remove')?></a></td>
            <tr>
                <?php } ?>
            <tr>
                <td colspan="3"><span class="pull-right"><strong><?=lang('total')?></strong></span></td>
                <td><strong><?=Applib::format_currency(config_item('default_currency'), $total)?></strong></td>
                <?php if($tax){ ?>
                    <td><?=Applib::format_currency(config_item('default_currency'), $tax_total)?></td>
                    <?php } ?>
                <td><a class="btn btn-danger btn-sm btn-block" href="<?=base_url()?>cart/remove_all"><?=lang('remove_all')?></a></td>
            </tr>
            <tr>
                <td colspan="<?=($tax)?'5':'4'?>"><a class="btn btn-primary btn-sm" href="<?=(!User::is_logged_in()) ? base_url() .'cart/hosting_packages' : base_url() .'orders/add_order'?>"><?=lang('continue_shopping')?></a></td>
                <td><a href="<?=base_url()?>cart/checkout" class="btn btn-success btn-sm btn-block" id="submitOrder"><?=lang('submit_order')?></a></td>
            </tr>
        </tbody>
    </table>
    </div> 
    </div>
</div>	 
 </div>