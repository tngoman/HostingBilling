<?php
$modules = $this->db->select('*')->where('parent', 0)->get('categories')->result();
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('add_category')?></h4>
        </div>
            <input type="hidden" name="module" value="items">
                <?php
                $attributes = array('class' => 'bs-example form-horizontal');
                echo form_open(base_url().'settings/add_category',$attributes); ?>
                    <div class="modal-body">

                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('cat_name')?> <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="cat_name">
                            </div>
                        </div>

                        <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('type')?></label>
                        <div class="col-lg-8">
                            <select class="select2-option form-control" name="parent" required>
                                <?php foreach ($modules as $m) : ?>
                                    <option value="<?=$m->id?>"><?=ucfirst($m->cat_name)?></option>
                                <?php endforeach; ?>
                            </select>
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
