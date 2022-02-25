<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'class'	=> 'form-control',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if (config_item('use_username', 'tank_auth')) {
	$login_label = 'Email or login';
} else {
	$login_label = 'Email';
}
echo modules::run('sidebar/flash_msg');
?>  
<div class="container inner">
        <div class="row"> 
		<div class="login-box"> 

		 
		 <section class="panel panel-default">
		<header class="panel-heading text-center"> <strong><?=lang('forgot_password')?></strong> </header>

		<?php 
		$attributes = array('class' => 'panel-body wrapper-lg');
		echo form_open($this->uri->uri_string(),$attributes); ?>
			<div class="form-group">
				<label class="control-label"><?=lang('email')?>/<?=lang('username')?></label>
				<?php echo form_input($login); ?>
				<span class="text-hidden">
				<?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></span>
			</div>
			<button type="submit" class="btn btn-danger"><?=lang('get_new_password')?></button>
			<div class="line line-dashed">
			</div> 
			<div class="row">
				<div class="col-md-6">
						<?php if (config_item('allow_client_registration') == 'TRUE'){ ?>
						<p class="text-muted text-center"><small><?=lang('do_not_have_an_account')?></small></p> 
						<a href="<?=base_url()?>auth/register/" class="btn btn-info btn-block"><?=lang('get_your_account')?></a>
						<?php } ?>
				</div>
				<div class="col-md-6">
						<p class="text-muted text-center"><small><?=lang('already_have_an_account')?></small></p> 
						<a href="<?=base_url()?>login" class="btn btn-<?=config_item('theme_color');?> btn-block"><?=lang('sign_in')?></a>
						<?php echo form_close(); ?>
				</div>
			</div>			


		

 </section>
	</div> 
 	</div>
 
	</div>