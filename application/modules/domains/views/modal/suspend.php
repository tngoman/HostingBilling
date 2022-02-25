<?php $domain = $this->db->where('id', $id)->get('orders')->row(); ?>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('provide_reason')?></h4>
		</div><?php
            $attributes = array('class' => 'bs-example form-horizontal');
                echo form_open(base_url().$domain->registrar.'/suspend_domain',$attributes); ?>
                <input type="hidden" value="<?=$id?>" name="id">
                    <br />                                             
                    <div class="row"> 
                            <div class="form-group">
                                    <label class="control-label col-md-3">
                                        <?=lang('reason')?>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" name="reason" class="form-control" required>
                                    </div>
                                </div>

                  

                                <div class="form-group">
                                    <label class="control-label col-md-3"> 
                                    </label>
                                    <div class="col-md-6">
                                    <button class="btn btn-<?=config_item('theme_color');?> pull-right"><?=lang('suspend')?></button>   
                                    </div>
                                </div>
                           
                                                     					                                   
                      </div>
		    </form>

		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
