<?php $order = Order::get_domain_order($id); ?> 
<?php $nameservers = array();
    if($order->nameservers != '') {
    $nameservers = explode(',', $order->nameservers); }
 ?>
 

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('update_nameservers')?></h4>
		</div><?php
            $attributes = array('class' => 'bs-example form-horizontal');
                echo form_open(base_url().$order->registrar.'/update_nameservers/'.$id ,$attributes); ?>
                   <br />                                                 
                    <div class="row"> 
                            <div class="form-group">
                                    <label class="control-label col-md-3">
                                        <?=lang('nameserver_1')?>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" value="<?=(isset($nameservers[0])) ? $nameservers[0] : config_item('nameserver_one');?>" name="nameserver_1" class="form-control" id="name_one" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">
                                        <?=lang('nameserver_2')?>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" value="<?=(isset($nameservers[1])) ? $nameservers[1] : config_item('nameserver_two');?>"name="nameserver_2" class="form-control" id="name_two" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">
                                        <?=lang('nameserver_3')?>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" value="<?=(isset($nameservers[2])) ? $nameservers[2] : config_item('nameserver_three');?>" name="nameserver_3" class="form-control" id="name_three">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">
                                        <?=lang('nameserver_4')?>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" value="<?=(isset($nameservers[3])) ? $nameservers[3] : config_item('nameserver_four');?>" name="nameserver_4" class="form-control" id="name_four">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3"> 
                                    </label>
                                    <div class="col-md-6">
                                    <button class="btn btn-<?=config_item('theme_color');?> pull-right"><?=lang('update_nameservers')?></button>   
                                    </div>
                                </div>
                           
                                                     					                                   
                      </div>
		    </form>

		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
