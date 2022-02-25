<div class="row" id="department_settings">
    <!-- Start Form -->
    <div class="col-lg-12">     
        <?php
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open_multipart('settings/departments', $attributes); ?>
      
             <div class="box-header">   <h2><?=lang('categories')?></h2> </div>
                <div class="box-body">
                    <input type="hidden" name="settings" value="<?=$load_setting?>">
                    <div class="form-group">
                    <a href="<?=base_url()?>settings/add_category" class="btn btn-<?=config_item('theme_color');?> btn-sm pull-right" data-toggle="ajaxModal" title="<?=lang('add_category')?>"><i class="fa fa-plus"></i> <?=lang('add_category')?></a>
               
                    </div>
                    <?php
                    $categories = $this -> db -> get('categories') -> result();
                    $core_categories = array(8,9,10);
                    if (!empty($categories)) {
                        foreach ($categories as $key => $d) { if(!in_array($d->id, $core_categories)) { ?>
                             <a href="<?=base_url()?>settings/edit_category/<?=$d->id?>" data-toggle="ajaxModal" class="btn btn-warning" title = ""><?=$d->cat_name?></a> 
                        <?php } } } ?>

                </div>
  
        </form>
    </div>
    <!-- End Form -->
</div>

