<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('send_test')?></h4>
        </div>
            <input type="hidden" name="module" value="items">
                <?php
                $attributes = array('class' => 'bs-example form-horizontal');
                echo form_open(base_url().'settings/send_test',$attributes); ?>
                    <div class="modal-body">

                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('mobile_phone')?> <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="phone" required>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-lg-4 control-label"><?=lang('message')?> <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <textarea class="form-control" name="message" required></textarea>
                            </div>
                        </div>

                         

                    </div>
                    <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
                        <button type="submit" class="btn btn-success"><?=lang('send')?></button>
                    </div>
                </form>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
