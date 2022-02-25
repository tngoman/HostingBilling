<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?=lang('open_exchange_rates')?></h4>
            </div>
                <?php
                $attributes = array('class' => 'bs-example form-horizontal');
                echo form_open(base_url().'settings/xrates',$attributes); ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?=lang('app_id')?></label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" value="<?=config_item('xrates_app_id')?>" name="xrates_app_id">
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