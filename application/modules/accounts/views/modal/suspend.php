<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('suspend')?></h4>
		</div><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'accounts/suspend',$attributes); ?>
		<div class="modal-body">


				<div class="row">
					<label class="control-label col-lg-2"><?=lang('reason')?></label>
					<div class="col-lg-9">
						<input type="text" class="form-control" name="reason" required="required">
						<input type="hidden" name="id" value="<?=$id?>">
					</div>
				</div>

		</div>
		<div class="modal-footer"> 
			<a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>  
			<button class="btn btn-success" type="submit"><?=lang('suspend')?></button> 
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
