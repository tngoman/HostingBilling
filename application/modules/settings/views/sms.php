   <!-- Start Form -->
   <?php
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open_multipart('settings/update', $attributes); ?>

   <input type="hidden" name="settings" value="<?=$load_setting?>">
   <div class="row">
       <div class="col-md-5">
           <?php
        $attributes = array('class' => 'bs-example form-horizontal');
                                echo form_open(base_url().'affiliates/config', $attributes); ?>
           <p class="text-danger"><?php echo $this->session->flashdata('form_errors'); ?></p>

           <div class="form-group">
               <label class="col-md-12"><?=lang('sms_gateway_active')?></label>
               <label class="switch">
                   <input type="hidden" value="off" name="sms_gateway" />
                   <input type="checkbox"
                       <?php if(config_item('sms_gateway') == 'TRUE'){ echo "checked=\"checked\""; } ?>
                       name="sms_gateway">
                   <span></span>
               </label>
           </div>



           <div class="form-group">
               <label class="col-md-6"><?=lang('request_method')?></label>
               <div class="col-lg-3">
                   <div class="input-group">
                       <select name="request_method" class="input-sm form-control" id="method">
                           <option value=""><?=lang('select')?></option>
                           <option value="get" <?=(config_item('request_method') == 'get') ? "selected" : ''?>>GET
                           </option>
                           <option value="post" <?=(config_item('request_method') == 'post') ? "selected" : ''?>>POST
                           </option>  
                           <option value="twilio" <?=(config_item('request_method') == 'twilio') ? "selected" : ''?>>Twilio
                           </option>                         
                       </select>
                   </div>
               </div>
           </div>

           <div id="post_fields">
                <div class="form-group">
                    <label class="col-md-6"><?=lang('encoding')?></label>
                    <div class="col-lg-3">
                        <select name="encoding" class="input-sm form-control">
                            <option value="none" <?=(config_item('encoding') == 'none') ? "selected" : ''?>>None</option>
                            <option value="json" <?=(config_item('encoding') == 'json') ? "selected" : ''?>>JSON</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-12">
                        <label><?=lang('custom_parameters')?></label><br>
                        <small> <?=lang('example')?>: uid=1234,auth=1234,somekey=somevalue</small>
                        <textarea class="col-lg-12 input-sm form-control" rows="1"
                            name="custom_parameters"><?=config_item('custom_parameters')?></textarea>
                    </div>
                </div>
           </div>


           <div id="twilio_fields">
                <div class="form-group">
                    <label class="col-md-6">SID</label>
                    <div class="col-lg-6">
                        <input name="twilio_sid" type="text" class="input-sm form-control" value="<?=config_item('twilio_sid')?>">                            
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-6">Token</label>
                    <div class="col-lg-6">
                        <input name="twilio_token" type="text" class="input-sm form-control" value="<?=config_item('twilio_token')?>">                            
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-6">Twilio Phone Number</label>
                    <div class="col-lg-6">
                        <input name="twilio_phone" type="text" class="input-sm form-control" value="<?=config_item('twilio_phone')?>">                            
                    </div>
                </div>

           </div> 


       </div>

       <div class="col-md-5">
           <div class="form-group">
               <label class="col-md-5"><?=lang('renewal_invoice')?></label>
               <label class="switch col-md-6">
                   <input type="hidden" value="off" name="sms_invoice" />
                   <input type="checkbox"
                       <?php if(config_item('sms_invoice') == 'TRUE'){ echo "checked=\"checked\""; } ?>
                       name="sms_invoice">
                   <span></span>
               </label>
           </div>

           <div class="form-group">
               <label class="col-md-5"><?=lang('invoice_reminder')?></label>
               <label class="switch col-md-6">
                   <input type="hidden" value="off" name="sms_invoice_reminder" />
                   <input type="checkbox"
                       <?php if(config_item('sms_invoice_reminder') == 'TRUE'){ echo "checked=\"checked\""; } ?>
                       name="sms_invoice_reminder">
                   <span></span>
               </label>
           </div>

           <div class="form-group">
               <label class="col-md-5"><?=lang('payments_received')?></label>
               <label class="switch col-md-6">
                   <input type="hidden" value="off" name="sms_payment_received" />
                   <input type="checkbox"
                       <?php if(config_item('sms_payment_received') == 'TRUE'){ echo "checked=\"checked\""; } ?>
                       name="sms_payment_received">
                   <span></span>
               </label>
           </div>

           <div class="form-group">
               <label class="col-md-5"><?=lang('service_suspended')?></label>
               <label class="switch col-md-6">
                   <input type="hidden" value="off" name="sms_service_suspended" />
                   <input type="checkbox"
                       <?php if(config_item('sms_service_suspended') == 'TRUE'){ echo "checked=\"checked\""; } ?>
                       name="sms_service_suspended">
                   <span></span>
               </label>
           </div>

           <div class="form-group">
               <label class="col-md-5"><?=lang('service_unsuspended')?></label>
               <label class="switch col-md-6">
                   <input type="hidden" value="off" name="sms_service_unsuspended" />
                   <input type="checkbox"
                       <?php if(config_item('sms_service_unsuspended') == 'TRUE'){ echo "checked=\"checked\""; } ?>
                       name="sms_service_unsuspended">
                   <span></span>
               </label>
           </div>


       </div>
   </div>

   <div id="url_fields">
        <div class="form-group">
            <label class="col-md-12"><?=lang('sms_gateway_url')?></label>
            <div class="col-lg-12">
                <textarea class="col-lg-12 input-sm form-control" rows="2"
                    name="sms_gateway_url"><?=config_item('sms_gateway_url')?></textarea>
            </div>
        </div>


        <div class="alert alert-info col-lg-12">
            <strong><?=lang('variables')?></strong><br> %NUMBER%, %MESSAGE%.
            <br>
            <strong><?=lang('sms_gateway_url_example')?></strong>
            <br>
            http://SMS_GATEWAY/sendsms.php?username=USERNAME&password=PASSWORD&number=%NUMBER%&message=%MESSAGE%
        </div>
   </div>



   <a href="<?=base_url()?>settings/send_test" data-toggle="ajaxModal"
       class="btn btn-warning btn-sm"><?=lang('send_test')?></a>&nbsp; &nbsp;<button
       class="btn btn-success btn-sm"><?=lang('save_settings')?></button>

   </div>
   </form>


   </div>

   </form>

   <!-- End Form -->

   <script type="text/javascript">
   $(document).ready(function(){

        <?php if(config_item('request_method') == 'post') 
        { ?>
            $('#post_fields').show(); 
            $('#url_fields').show(); 
        <?php }
        else
        { ?>
            $('#post_fields').hide(); 
        <?php } 
        
        if(config_item('request_method') == 'get') 
        { ?> 
            $('#url_fields').show(); 
        <?php }


        if(config_item('request_method') != 'get' && config_item('request_method') != 'post') 
        { ?> 
            $('#url_fields').hide(); 
        <?php }
   
        if(config_item('request_method') == 'twilio') 
        { ?>
            $('#twilio_fields').show(); 
        <?php }
        else
        { ?>
            $('#twilio_fields').hide();
        <?php } ?>
    
        $('#method').on('change', function(){
            var val = $(this).find('option:selected').val();
            if(val == 'post') {
                $('#post_fields').show(500); 
                $('#url_fields').show(500);
            }
            else
            {
                $('#post_fields').hide(500); 
            }

            if(val == 'get') { 
                $('#url_fields').show(500);
            }

            if(val != 'get' && val != 'post') { 
                $('#url_fields').hide(500);
            } 
          

            if(val == 'twilio') {
                $('#twilio_fields').show(500); 
            }
            else
            {
                $('#twilio_fields').hide(500);
            }
        }); 

   });
   </script>