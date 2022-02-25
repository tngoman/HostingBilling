<div class="row">
    <!-- Start Form -->
        <div class="col-lg-12">
            <section class="panel panel-default">
                <header class="panel-heading font-bold"><i class="fa fa-inbox"></i> <?=lang('alert_settings')?></header>
                <div class="panel-body">
                    <?php
                    $attributes = array('class' => 'bs-example form-horizontal','data-validate'=>'parsley');
                    echo form_open('settings/update', $attributes); ?>
                    <?php echo validation_errors(); ?>
                    <input type="hidden" name="settings" value="<?=$load_setting?>">

                    <div class="form-group text-danger">
                        <label class="col-lg-5 control-label" 
                        data-toggle="tooltip" data-title="DISABLE ALL EMAILS" data-placement="right"><?=lang('disable_emails')?></label>
                        <div class="col-lg-7">
                            <label class="switch">
                                <input type="hidden" value="off" name="disable_emails" />
                                <input type="checkbox" <?php if(config_item('disable_emails') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="disable_emails">
                                <span></span>
                            </label>

                        </div>
                    </div>

                     <div class="line line-dashed line-lg pull-in"></div>

                    <div class="form-group">
                        <label class="col-lg-5 control-label" 
                        data-toggle="tooltip" data-title="An email containing user login credentials will be sent to new users" data-placement="right"><?=lang('email_account_details')?></label>
                        <div class="col-lg-7">
                            <label class="switch">
                                <input type="hidden" value="off" name="email_account_details" />
                                <input type="checkbox" <?php if(config_item('email_account_details') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="email_account_details">
                                <span></span>
                            </label>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-5 control-label" data-toggle="tooltip" data-title="Send email to admins when a new payment is received" data-placement="right" ><?=lang('notify_payment_received')?></label>
                        <div class="col-lg-7">
                            <label class="switch">
                                <input type="hidden" value="off" data-toggle="tooltip" name="notify_payment_received" />
                                <input type="checkbox" <?php if(config_item('notify_payment_received') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_payment_received">
                                <span></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-5 control-label"  data-toggle="tooltip" data-title="Send email to admins/staff when a new ticket created" data-placement="right"><?=lang('email_staff_tickets')?></label>
                        <div class="col-lg-7">
                            <label class="switch">
                                <input type="hidden" value="off" name="email_staff_tickets" />
                                <input type="checkbox" <?php if(config_item('email_staff_tickets') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="email_staff_tickets">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-5 control-label"  data-toggle="tooltip" data-title="Send email to reporter/staff when a ticket is replied to" data-placement="right"><?=lang('notify_ticket_reply')?></label>
                        <div class="col-lg-7">
                            <label class="switch">
                                <input type="hidden" value="off" name="notify_ticket_reply" />
                                <input type="checkbox" <?php if(config_item('notify_ticket_reply') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_ticket_reply">
                                <span></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-5 control-label"  data-toggle="tooltip" data-title="Send email to ticket reporter when ticket closed" data-placement="right"><?=lang('notify_ticket_closed')?></label>
                        <div class="col-lg-7">
                            <label class="switch">
                                <input type="hidden" value="off" name="notify_ticket_closed" />
                                <input type="checkbox" <?php if(config_item('notify_ticket_closed') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_ticket_closed">
                                <span></span>
                            </label>
                        </div>
                    </div>

                     <div class="form-group">
                        <label class="col-lg-5 control-label"  data-toggle="tooltip" data-title="Send email to staff or client when ticket re-opened" data-placement="right"><?=lang('notify_ticket_reopened')?></label>
                        <div class="col-lg-7">
                            <label class="switch">
                                <input type="hidden" value="off" name="notify_ticket_reopened" />
                                <input type="checkbox" <?php if(config_item('notify_ticket_reopened') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_ticket_reopened">
                                <span></span>
                            </label>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-lg-offset-6 col-lg-10">
                            <button type="submit" class="btn btn-sm btn-<?=config_item('theme_color')?>"><i class="fa fa-check"></i> <?=lang('save_changes')?></button>
                        </div>
                    </div>
                    </form>
                </div> </section>
        </div>
    <!-- End Form -->
</div>