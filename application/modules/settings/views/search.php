   <!-- Start Form -->
         <?php
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open_multipart('settings/update', $attributes); ?>
           
                    <input type="hidden" name="settings" value="<?=$load_setting?>">  

                            <div class="form-group">
                            <label class="col-lg-3 control-label"><?=lang('domain_checker')?></label>
                            <div class="col-lg-5">
                                
                                </div>
                                <div class="col-lg-9" id="default_registrar">
                                <input type ="radio" name="domain_checker" value="default" <?=(config_item('domain_checker') == 'default') ? 'checked="checked"' : ''?>> <span class="label label-default"><?=lang('basic_checker')?></span> <hr>
                                <?php $registrars = Plugin::domain_registrars();
                                 foreach ($registrars as $registrar)
                                 {?> <input type ="radio" name="domain_checker" value="<?=$registrar->system_name;?>" <?=(config_item('domain_checker') == $registrar->system_name) ? 'checked="checked"' : ''?>> <span class="label label-default"><?=ucfirst($registrar->system_name);?></span><hr>                                    
                                    <?php } if(Plugin::get_plugin('whoisxmlapi')) {?>
                                    <input type ="radio" name="domain_checker" value="whoisxmlapi" <?=(config_item('domain_checker') == 'whoisxmlapi') ? 'checked="checked"' : ''?>> <span class="label label-default">WhoisXMLApi</span> &nbsp; <small><?=lang('whoisxmlapi_signup')?></small><hr>
                                    <?php } ?>
                                </div>
                            </div>
                            
                                
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?=lang('whoisxmlapi_key')?></label>
                                <div class="col-lg-5">                                   
                                    <input type="<?=config_item('demo_mode') == 'TRUE' ? 'password' : 'text';?>" name="whoisxmlapi_key" class="form-control" value="<?=config_item('whoisxmlapi_key')?>">
                                    <p>
                                    <span class="help-block m-b-none small text-danger"><?=lang('whoisxmlapi_description')?> </span>
                                    </p>
                                </div>
                            </div>                   
                  
           
                    <div class="text-center">
                        <button type="submit" class="btn btn-sm btn-<?=config_item('theme_color');?>"><?=lang('save_changes')?></button>
                    </div>
                 
        </form>
 
    <!-- End Form -->
 
