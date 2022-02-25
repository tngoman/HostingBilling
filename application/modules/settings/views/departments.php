<div class="row" id="department_settings">
    <!-- Start Form -->
    <div class="col-lg-12">
        <?php
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open_multipart('settings/departments', $attributes); ?>
            <section class="box">

            <?php
            $view = isset($_GET['view']) ? $_GET['view'] : '';
            $data['load_setting'] = $load_setting;
            switch ($view) {
            
                case 'categories':
                        $this->load->view('categories',$data);
                        break; 
                
                default: ?> 
                
                <div class="box-body">
                    <input type="hidden" name="settings" value="<?=$load_setting?>">
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?=lang('department_name')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" name="deptname" class="form-control w_260" placeholder="<?=lang('department_name')?>" required>
                        </div>
                    </div>
                    <?php
                    $departments = $this -> db -> get('departments') -> result();
                    if (!empty($departments)) {
                        foreach ($departments as $key => $d) { ?>
                            <label class="label label-primary"><a href="<?=base_url()?>settings/edit_dept/<?=$d->deptid?>" data-toggle="ajaxModal" title = ""><?=$d->deptname?></a></label>
                        <?php } } ?>

                </div>
                <div class="box-footer">
                    <div class="text-center">
                        <button type="submit" class="btn btn-sm btn-<?=config_item('theme_color');?>"><?=lang('save_changes')?></button>
                    </div>
                </div>

                
            <?php
            break;
    }
    ?>
    
            </section>
        </form>
    </div>
    <!-- End Form -->
</div>

