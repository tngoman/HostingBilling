<?php
$modules = $this->db->select('*')->where('parent', 0)->get('categories')->result();
$pricing_tables = array('one', 'two', 'three', 'four', 'five', 'six', 'seven');
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('edit_currency')?></h4>
        </div>

                <?php
                $i = $this->db->where('id',$cat)->get('categories')->row();
                $attributes = array('class' => 'bs-example form-horizontal');
                echo form_open(base_url().'settings/edit_category',$attributes); ?>
                <input type="hidden" name="id" value="<?=$i->id?>">
                <input type="hidden" name="module" value="items">
                    <div class="modal-body">

                <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('cat_name')?> <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" value="<?=$i->cat_name?>" name="cat_name">
                            </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-4 control-label"><?=lang('type')?></label>
                    <div class="col-lg-8">
                        <select class="select2-option form-control" name="parent" required>
                            <?php foreach ($modules as $m) : ?>
                    <option value="<?=$m->id?>" <?=($m->id == $i->parent) ? 'selected="selected"' : '' ;?>><?=ucfirst($m->cat_name)?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <?php if($i->parent == 9 || $i->parent == 10) { ?>

                <div class="form-group">
                    <label class="col-lg-4 control-label"><?=lang('pricing_table')?></label>
                    <div class="col-lg-8">
                        <select class="select2-option form-control" name="pricing_table">
                            <?php foreach($pricing_tables as $table) { ?>
                                <option value="<?=$table?>" <?=($table == $i->pricing_table) ? 'selected' : ''?>><?=ucfirst($table)?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

               <?php } ?>

                <div class="form-group">
                      <label class="col-lg-4 control-label"><?=lang('delete_category')?></label>
                      <div class="col-lg-8">
                        <label class="switch">
                          <input type="checkbox" name="delete_cat">
                          <span></span>
                        </label>
                      </div>
                    </div>


                    </div>
                    <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
                        <button type="submit" class="btn btn-success"><?=lang('save_changes')?></button>
                    </div>
                </form>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
