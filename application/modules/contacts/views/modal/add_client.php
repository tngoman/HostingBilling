<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('add_contact')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'auth/register_user',$attributes); ?>


		<div class="modal-body">
			 <input type="hidden" name="r_url" value="<?=base_url()?>companies/view/<?=$company?>">
			 <input type="hidden" name="company" value="<?=$company?>">
			 <input type="hidden" name="role" value="2">

			 <span id="status"></span>

			 <div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('full_name')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=set_value('fullname')?>" placeholder="E.g John Doe" name="fullname" required>
				</div>
				</div>
          		<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('username')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">

                          <input class="form-control" id='username' type="text" value="<?=set_value('username')?>" placeholder="johndoe" name="username" required>

				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('email')?></label>
				<div class="col-lg-8">

                          <input class="form-control" id='email' type="email" value="<?=set_value('email')?>" placeholder="me@domain.com" name="email" required>

				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('password')?> </label>
				<div class="col-lg-8">
					<input type="password" class="form-control" value="<?=set_value('password')?>" name="password">
				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('confirm_password')?> </label>
				<div class="col-lg-8">
					<input type="password" class="form-control" value="<?=set_value('confirm_password')?>" name="confirm_password">
				</div>
				</div>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('phone')?> </label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=set_value('phone')?>" name="phone" placeholder="+52 782 983 434">
				</div>

				</div>

				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('email_contact')?></label>
					<label class="">
						<input type="radio" name="email_contact[]" value="Yes" required=""> Yes </label>
                    <label class="">
                        <input type="radio" name="email_contact[]" checked="checked" value="No" required=""> No </label>
                </div>

		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-<?=config_item('theme_color');?>"><?=lang('add_contact')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<script type="text/javascript">
$(document).ready(function(){
	$("#username").change(function(){
		var username = $("#username").val();
		var msgbox = $("#status");
			if(username.length > 4){
				$("#status").html('<img src="<?=base_url()?>resource/images/reload.gif" />&nbsp;Checking username availability...');
				$.ajax({
				    type: "POST",
				    url: "<?=base_url()?>contacts/username_check",
				    data: { 'username': username },
				    success: function(msg){
						msgbox.html(msg).show().delay(5000).fadeOut();
				   }

			});

}else{
		$("#status").html('<font color="#cc0000">Please enter atleast 4 letters</font>');
}
	return false;
});

$("#email").change(function(){
	var email = $("#email").val();
	var msgbox = $("#status");
		if(email.length > 5){
			$("#status").html('<img src="<?=base_url()?>resource/images/reload.gif" />&nbsp;Checking email availability...');
			$.ajax({
				type: "POST",
				url: "<?=base_url()?>contacts/email_check",
				data: { 'email': email },
				success: function(msg){
					msgbox.html(msg).show().delay(5000).fadeOut();
			   }

		});

}else{
	$("#status").html('<font color="#cc0000">Please enter atleast 5 letters</font>');
}
return false;
});
});
</script>
