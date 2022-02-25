<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('add_category')?></h4>
        </div>
            <input type="hidden" name="module" value="items">
                <?php
                $attributes = array('class' => 'bs-example form-horizontal');
                echo form_open(base_url().'companies/send_email',$attributes); ?>
                    <div class="modal-body">

                        <div class="form-group">
                            <label class="col-lg-2 control-label"><?=lang('email')?> <span class="text-danger">*</span></label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="email" value="<?=$email?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">CC</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="cc">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-lg-2 control-label"><?=lang('message')?> <span class="text-danger">*</span></label>
                            <div class="col-lg-10">
                                <textarea class="form-control foeditor" rows="10" name="message" required></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label"></label>
                            <div class="col-lg-10">
                                <input type="file" class="filestyle" data-buttonText="<?=lang('choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="attach">
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
