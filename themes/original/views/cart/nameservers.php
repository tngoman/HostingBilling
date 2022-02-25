<div class="box"> 	
<div class="container inner">
        <div class="row"> 

		 <section class="panel panel-default bg-white m-t-lg radius_3">
		 <header class="panel-heading text-center">			 
		 <h3><?= lang('nameservers')?></h3>		 	
		</header> 
			
		<div class="panel-body">
		<?php
            $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open(base_url().'cart/add_nameservers',$attributes); ?>
        <?=lang('nameserver_help')?>  
        <hr>                                                 
                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-group">
                                    <label class="control-label col-md-3">
                                        <?=lang('nameserver_1')?>
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" name="nameserver_1" class="form-control" id="name_one" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">
                                        <?=lang('nameserver_2')?>
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" name="nameserver_2" class="form-control" id="name_two" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">
                                        <?=lang('nameserver_3')?>
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" name="nameserver_3" class="form-control" id="name_three">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">
                                        <?=lang('nameserver_4')?>
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" name="nameserver_4" class="form-control" id="name_four">
                                    </div>
                                </div>

                                <div class="input-group pull-right">                            
                                        <a href="<?=base_url()?>cart/default_nameservers" class="btn btn-info"><?=lang('default_nameservers')?></a>
                                        <button class="btn btn-<?=config_item('theme_color');?>"><?=lang('custom_nameservers')?></button>                             					                                   
                                </div>
                    </div>
		    </form>

		</div>
		</section>
	</div>
</div> 
</div>
  