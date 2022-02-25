<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
    <h4 class="modal-title"><?=lang('permission_settings')?> <?php
                if (isset($user_id)) {
                    echo ' for '.ucfirst(Applib::get_table_field('users',array('id'=>$user_id),'username'));
                }
                ?></h4>
    </div>
    <div class="modal-body">


    <?php
    $attributes = array('class' => 'bs-example form-horizontal');
    echo form_open('users/account/permissions', $attributes); ?>
        <input type="hidden" name="settings" value="permissions">
        <input type="hidden" name="user_id" value="<?=$user_id?>">

        <!-- checkbox -->
        <?php
        $permission = $this -> db -> where(array('status'=>'active')) -> get('permissions') -> result();

        $current_json_permissions = Applib::get_table_field(Applib::$profile_table,array('user_id'=>$user_id),'allowed_modules');

        if ($current_json_permissions == NULL) {
            $current_json_permissions = '{"settings":"permissions"}';
        }
        $current_permissions = json_decode($current_json_permissions, true);
        foreach ($permission as $key => $p) { ?>
            <div class="checkbox">
                <label class="checkbox-custom">
                    <input type="hidden" value="off" name="<?=$p->name?>" />
                    <input name="<?=$p->name?>" <?php
                    if ( array_key_exists($p->name, $current_permissions) && $current_permissions[$p->name] == 'on') {
                        echo "checked=\"checked\"";
                    }
                    ?>  type="checkbox">
                    <?=lang($p->name)?>
                </label>
            </div>
            <?php } ?>

        <div class="modal-footer"> 
    <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a> 
    <button type="submit" class="btn btn-<?=config_item('theme_color');?>"><?=lang('save_changes')?></button>
    </form>

        </div>

    
    </div>

  </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->