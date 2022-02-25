<div class="row">
	<!-- Start Form test -->
		<div class="col-lg-12">
			<?php
			$attributes = array('class' => 'bs-example form-horizontal','data-validate'=>'parsley');
			echo form_open('settings/update', $attributes); ?>
				<section class="box">
					 
					<div class="box-body">
						<?php echo validation_errors(); ?>
						<input type="hidden" name="settings" value="<?=$load_setting?>">
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('company_email')?> <span class="text-danger">*</span></label>
							<div class="col-lg-3">
								<input type="email" class="form-control" value="<?=config_item('company_email')?>" name="company_email" data-type="email" data-required="true">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('use_alternate_emails')?></label>
							<div class="col-lg-3">
								<label class="switch">
									<input type="hidden" value="off" name="use_alternate_emails" />
									<input type="checkbox" <?php if(config_item('use_alternate_emails') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="use_alternate_emails" id="use_alternate_emails">
									<span></span>
								</label>
							</div>
						</div>
                                                <div id="alternate_emails" <?php echo (config_item('use_alternate_emails') != 'TRUE') ? 'class="hidden"' : ''?>>
                                                    <div class="form-group">
                                                        <label class="col-lg-3 control-label"><?=lang('billing_email')?></label>
                                                            <div class="col-lg-3">
                                                                    <input type="email" class="form-control" value="<?=config_item('billing_email')?>" name="billing_email" data-type="email">
                                                            </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-lg-3 control-label"><?=lang('billing_email_name')?></label>
                                                            <div class="col-lg-4">
                                                                    <input type="text" class="form-control" value="<?=config_item('billing_email_name')?>" name="billing_email_name">
                                                            </div>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="col-lg-3 control-label"><?=lang('support_email')?></label>
                                                            <div class="col-lg-3">
                                                                    <input type="email" class="form-control" value="<?=config_item('support_email')?>" name="support_email" data-type="email">
                                                            </div>
                                                    </div>
                                                    <div class="form-group">
                                                            <label class="col-lg-3 control-label"><?=lang('support_email_name')?></label>
                                                            <div class="col-lg-4">
                                                                    <input type="text" class="form-control" value="<?=config_item('support_email_name')?>" name="support_email_name">
                                                            </div>
                                                    </div>
						</div>
				 
					 
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('email_protocol')?> <span class="text-danger">*</span></label>
							<div class="col-lg-3">
								<select name="protocol" class="form-control">
									<?php $prot = config_item('protocol'); ?>
									<option value="mail"<?=($prot == "mail" ? ' selected="selected"' : '')?>><?=lang('php_mail')?></option>
									<option value="smtp"<?=($prot == "smtp" ? ' selected="selected"' : '')?>><?=lang('smtp')?></option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('smtp_host')?> </label>
							<div class="col-lg-3">
								<input type="text" class="form-control"  value="<?=config_item('smtp_host')?>" name="smtp_host">
								<span class="help-block m-b-none">SMTP Server Address</strong>.</span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('smtp_user')?></label>
							<div class="col-lg-3">
								<input type="text" class="form-control"  value="<?=config_item('smtp_user')?>" name="smtp_user">
							</div>
						</div>
						<div class="form-group">
							<?php $this->load->library('encryption'); ?>
							<label class="col-lg-3 control-label"><?=lang('smtp_pass')?></label>
							<div class="col-lg-3">
								<input type="password" class="form-control" value="<?=$this->encryption->decrypt(config_item('smtp_pass'));?>" name="smtp_pass">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('smtp_port')?></label>
							<div class="col-lg-3">
								<input type="text" class="form-control" value="<?=config_item('smtp_port')?>" name="smtp_port">
							</div>
						</div>


						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('email_encryption')?></label>
							<div class="col-lg-3">
								<select name="smtp_encryption" class="form-control">
									<?php $crypt = config_item('smtp_encryption'); ?>
					<option value=""<?=($crypt == "" ? ' selected="selected"' : '')?>><?=lang('none')?></option>
					<option value="ssl"<?=($crypt == "ssl" ? ' selected="selected"' : '')?>>SSL</option>
					<option value="tls"<?=($crypt == "tls" ? ' selected="selected"' : '')?>>TLS</option>
								</select>
								</div>
					</div>





					<div class="box-footer">
						<div class="text-center">
							<button type="submit" class="btn btn-sm btn-<?=config_item('theme_color');?>"><?=lang('save_changes')?></button>
						</div>
					</div>
				</section>
			</form>

        <?php
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open_multipart('settings/update', $attributes); ?>
            <section class="box box-primary box-solid">
                <header class="box-header font-bold"><i class="fa fa-random"></i> <?=lang('email_piping_settings')?></header>
                <div class="box-body">
                    <?php echo validation_errors(); ?>
                    <input type="hidden" name="settings" value="<?=$load_setting?>">


                    <div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('activate_email_tickets')?></label>
							<div class="col-lg-3">
								<label class="switch">
									<input type="hidden" value="off" name="email_piping" />
									<input type="checkbox" <?php if(config_item('email_piping') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="email_piping">
									<span></span>
								</label>
							</div>
						</div>

                    <div class="form-group">
							<label class="col-lg-3 control-label">IMAP</label>
							<div class="col-lg-3">
								<label class="switch">
									<input type="hidden" value="off" name="mail_imap" />
									<input type="checkbox" <?php if(config_item('mail_imap') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="mail_imap">
									<span></span>
								</label>
							</div>
						</div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">IMAP Host</label>
                        <div class="col-lg-5">
                            <input type="text" class="form-control" value="<?=config_item('mail_imap_host')?>" name="mail_imap_host">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">IMAP Username</label>
                        <div class="col-lg-5">
                            <input type="text" autocomplete="off" class="form-control" value="<?=config_item('mail_username')?>" name="mail_username">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">IMAP Password</label>
                        <div class="col-lg-5">
                        <?php
                        $this->load->library('encryption');
                        $pass = $this->encryption->decrypt(config_item('mail_password'));
                        ?>
                            <input type="password" autocomplete="off" class="form-control" value="<?=$pass?>" name="mail_password">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Mail Port</label>
                        <div class="col-lg-5">
                            <input type="text" class="form-control" value="<?=config_item('mail_port')?>" name="mail_port">
                        </div>

                        <span class="help-block m-b-none small text-danger">Port (143 or 110) (Gmail: 993)</span>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label">Mail Flags</label>
                        <div class="col-lg-5">
                            <input type="text" class="form-control" value="<?=config_item('mail_flags')?>" name="mail_flags">
                        </div>

                        <span class="help-block m-b-none small text-danger">/notls or /novalidate-cert</span>
                    </div>

                    <div class="form-group">
							<label class="col-lg-3 control-label">Mail SSL</label>
							<div class="col-lg-3">
								<label class="switch">
									<input type="hidden" value="off" name="mail_ssl" />
									<input type="checkbox" <?php if(config_item('mail_ssl') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="mail_ssl">
									<span></span>
								</label>
							</div>
						</div>

					<div class="form-group">
                        <label class="col-lg-3 control-label">Mailbox</label>
                        <div class="col-lg-5">
                            <input type="text" class="form-control" value="<?=config_item('mailbox')?>" name="mailbox">
                        </div>

                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">IMAP Search</label>
                        <div class="col-lg-5">
                            <input type="text" class="form-control" value="<?=config_item('mail_search')?>" name="mail_search">
                        </div>

                        <span class="help-block m-b-none small text-danger">UNSEEN</span>
                    </div>





                </div>

                <div class="box-footer">
                    <div class="text-center">
                        <button type="submit" class="btn btn-sm btn-<?=config_item('theme_color')?>"><?=lang('save_changes')?></button>
                    </div>
                 </div>
            </section>
        </form>


		</div>
	<!-- End Form -->
</div>
