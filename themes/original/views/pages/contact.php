<?php 

$name = array(
	'name'	=> 'name',
	'placeholder' => lang('name'),
	'value' => set_value('name')
);

$email = array(
	'name'	=> 'email',
	'placeholder' => lang('email'),
	'value' => set_value('email') 
);
 
$subject = array(
	'name'	=> 'subject',
	'id'	=> 'subject',
	'placeholder' => lang('subject') 
);

$message = array(
	'name'	=> 'message',
	'id'	=> 'message',
	'placeholder' => lang('message'),
	'rows' => '7',
    'cols' => '40',
	'size'	=> 20,
	'class' => 'txt-box textArea'
);

$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'class'	=> 'form-control input-lg',
	'maxlength'	=> 10,
);
 
?>

<div class="contact-page">


  <div class="row">
        <div class="col-md-12">
             <?php blocks('full_width_top', get_slug()); ?>
     </div>
 </div>
  

<!-- container -->
<div class="container inner">
        <div class="row">

            <!-- Sidebar -->
            <?php if(config_item('contact_sidebar_left') == TRUE) { ?>
            <aside class="col-sm-3 sidebar_left">
            <?php blocks('sidebar_left', get_slug()); ?>
            </aside>
            <?php } ?>
            <!-- /Sidebar -->

            <!-- main content -->
            <section class="<?php 
                if(config_item('contact_sidebar_right') == TRUE && config_item('contact_sidebar_left') == TRUE) { echo 'col-md-6'; }
                else if(config_item('contact_sidebar_right') == TRUE || config_item('contact_sidebar_left') == TRUE) { echo 'col-md-9'; }
                else { echo 'col-md-12 0'; } 
                ?>">

              <?php blocks('content_top', get_slug()); ?> 

				<?php
			$attributes = array('class' => 'leave-comment contact-form', 'id' => 'contact-form');
			echo form_open($this->uri->uri_string(),$attributes); ?>

	
			<?php if($this->session->flashdata('message')) {?>
			<div class="alert alert-info">
			<?php
			echo $this->session->flashdata('message'); 
			?>
			</div>
			<?php } ?>


			<fieldset>
				<div id="formstatus"></div>
				<div class="Contact-us">
					<div class="form-input col-md-6">
						<?php echo form_input($name); ?>
						<span class="text-danger">
							<?php echo form_error($name['name']); ?><?php echo isset($errors[$name['name']])?$errors[$name['name']]:''; ?>
						</span>
					</div>
					<div class="form-input col-md-6">
					<?php echo form_input($email); ?>
						<span class="text-danger">
							<?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?>
						</span>
					</div>
					<div class="form-input col-md-12">
					<?php echo form_input($subject); ?>
						<span class="text-danger"><?php echo form_error($subject['name']); ?><?php echo isset($errors[$subject['name']])?$errors[$subject['name']]:''; ?>
					</span>
					</div>
					<div class="form-input col-md-12">						
						<?php echo form_textarea($message); ?>
						<span class="text-danger">
							<?php echo form_error($message['name']); ?><?php echo isset($errors[$message['name']])?$errors[$message['name']]:''; ?>
						</span>
					</div>
					<div class="form-submit col-md-12">
						<input type="submit" id="submit" class="btn btn-<?=config_item('theme_color');?> pull-right" value="<?=lang('send_message')?>">
					</div>

					<table>

					<?php if ($show_captcha) {
						if ($use_recaptcha) { ?>
							
					<?php echo $this->recaptcha->render(); ?>

					<?php } else { ?>
					<tr><td colspan="2"><p><?=lang('enter_the_code_exactly')?></p></td></tr>


					<tr>
						<td colspan="3"><?php echo $captcha_html; ?></td>
						<td class="pl_5"><?php echo form_input($captcha); ?></td>
						<span class="text-danger"><?php echo form_error($captcha['name']); ?></span>
					</tr>
					<?php }
					} ?>
					</table>

				</div>
			</fieldset>  
		
			<?php echo form_close(); ?> 
			   
			
			<div class="inner">
               <?php blocks('content_bottom', get_slug()); ?>
            </div>
			   
            </section>
            <!-- /main -->
	
            <!-- Sidebar -->
            <?php if(config_item('contact_sidebar_right') == TRUE) { ?>
            <aside class="col-sm-3 sidebar_right">
            <?php blocks('sidebar_right', get_slug()); ?>
            </aside>
            <?php } ?>
            <!-- /Sidebar -->

        </div>
		</div>
 
 
            <!-- Full width -->   
    <section class="white-wrapper">
         <div class="row">
              <div class="col-md-12">
              <?php blocks('full_width_content_bottom', get_slug()); ?>
              </div>
            </div>
   </section>

 

 <!-- Normal width -->    
 <section class="whitesmoke-wrapper">	
          <div class="container inner">
            <div class="row">
              <div class="col-md-12">
              <?php blocks('page_bottom', get_slug()); ?>
              </div>
            </div>
          </div>
    </section>


 
<!-- Normal width -->  
<section class="white-wrapper">
    <div class="container inner">
         <div class="row">
              <div class="col-md-12">
              <?php blocks('footer_top', get_slug()); ?>
              </div>
            </div>
        </div>
   </section>

</div>


 