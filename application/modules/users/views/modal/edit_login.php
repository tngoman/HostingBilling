<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
    <h4 class="modal-title"><?=lang('edit_user')?></h4>
    </div><?php
       $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'users/account/auth',$attributes); ?>
          <?php $user = User::view_user($id); ?>
    <div class="modal-body">
       <input type="hidden" name="user_id" value="<?=$id?>">


       <div class="form-group">
        <label class="col-lg-4 control-label"><?=lang('username')?></label>
        <div class="col-lg-8">
          <input type="text" class="form-control" value="<?=$user->username?>" name="username">
        </div>
      </div>

              <div class="form-group">
        <label class="col-lg-4 control-label"><?=lang('email')?> <span class="text-danger">*</span></label>
        <div class="col-lg-8">
          <input type="email" class="form-control" value="<?=$user->email?>" name="email" required>
        </div>
        </div>
       
       <div class="form-group">
        <label class="col-lg-4 control-label"><?=lang('password')?></label>
        <div class="col-lg-8">
          <input type="password" class="form-control" value="<?=set_value('password')?>" name="password">
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-4 control-label"><?=lang('confirm_password')?></label>
        <div class="col-lg-8">
          <input type="password" class="form-control" value="<?=set_value('confirm_password')?>" name="confirm_password">
        </div>
      </div>
              

        
        <div class="form-group">
        <label class="col-lg-4 control-label"><?=lang('role')?> <span class="text-danger">*</span></label>
        <div class="col-lg-8">
        <select name="role_id" class="form-control">
          <?php
          foreach (User::get_roles() as $key => $role) { ?>
            <option value="<?=$role->r_id?>"<?=($user->role_id == $role->r_id ? ' selected="selected"' : '')?>><?=ucfirst($role->role)?></option>
          <?php } ?>          
        </select>
        </div>
        </div>
      
    </div>
    <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
    <button type="submit" class="btn btn-<?=config_item('theme_color');?>"><?=lang('save_changes')?></button>
    </form>
    </div>
  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->