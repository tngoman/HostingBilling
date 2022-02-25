<?php $info = Ticket::view_by_id($id); ?>
  <div class="box">
		<div class="box-header b-b clearfix">
		<?=lang('ticket_details')?> - <?=$info->ticket_code?>	 
		<a href="<?=base_url()?>tickets/view/<?=$info->id?>" data-original-title="<?=lang('view_details')?>" data-toggle="tooltip" data-placement="bottom" class="btn btn-<?=config_item('theme_color');?> btn-sm pull-right"><i class="fa fa-info-circle"></i> <?=lang('ticket_details')?></a>

	 </div> 
	<div class="box-body">
	
<!-- Start ticket form -->
<?php echo $this->session->flashdata('form_error'); ?>

	<?php 
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open_multipart(base_url().'tickets/edit/',$attributes);
           ?>
			 
			 <input type="hidden" name="id" value="<?=$info->id?>">

			    <div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('ticket_code')?> <span class="text-danger">*</span></label>
				<div class="col-lg-3">
					<input type="text" class="form-control" value="<?=$info->ticket_code?>" name="ticket_code">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('subject')?> <span class="text-danger">*</span></label>
				<div class="col-lg-7">
					<input type="text" class="form-control" value="<?=$info->subject?>" name="subject">
				</div>
				</div>

				

			

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('priority')?> <span class="text-danger">*</span> </label>
				<div class="col-lg-6">
					<div class="m-b"> 
					<select name="priority" class="form-control" >
					<option value="<?=$info->priority?>"><?=lang('use_current')?></option>
					<?php 
					$priorities = $this->db->get('priorities')->result();
						foreach ($priorities as $p): ?>
					<option value="<?=$p->priority?>"><?=lang(strtolower($p->priority))?></option>
					<?php endforeach; ?>
					</select> 
					</div> 
				</div>
			</div>

			 <div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('department')?> </label>
				<div class="col-lg-6">
					<div class="m-b"> 
					<select name="department" class="form-control" >
					<?php 
					$departments = App::get_by_where('departments',array('deptid >'=>'0'));
						foreach ($departments as $d): ?>
					<option value="<?=$d->deptid?>"<?=($info->department == $d->deptid ? ' selected="selected"' : '')?>><?=strtoupper($d->deptname)?></option>
					<?php endforeach;  ?>
					</select> 
					</div> 
				</div>
			</div>


			<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('reporter')?> <span class="text-danger">*</span> </label>
				<div class="col-lg-6">
					<div class="m-b"> 
					<select class="select2-option w_260" name="reporter" >
					<optgroup label="<?=lang('users')?>"> 
					<?php foreach (User::all_users() as $user): ?>
					<option value="<?=$user->id?>"<?=($info->reporter == $user->id ? ' selected="selected"' : '')?>><?php echo User::displayName($user->id); ?></option>
					<?php endforeach; ?>
					</optgroup> 
					</select> 
					</div> 
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('ticket_message')?> </label>
				<div class="col-lg-9">
				<textarea name="body" class="form-control textarea"><?=$info->body?></textarea>
				
				</div>
				</div>

			<div id="file_container">
				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('attachment')?></label>
				<div class="col-lg-6">
					<input type="file" name="ticketfiles[]">
				</div>
				</div>

			</div>

<a href="#" class="btn btn-primary btn-xs" id="add-new-file"><?=lang('upload_another_file')?></a>
<a href="#" class="btn btn-default btn-xs" id="clear-files"><?=lang('clear_files')?></a>

<div class="line line-dashed line-lg pull-in"></div>

	<button type="submit" class="btn btn-sm btn-<?=config_item('theme_color')?>"><i class="fa fa-ticket"></i> <?=lang('edit_ticket')?></button>

				
</form>

		<!-- End ticket -->
		
</div>
</div>


<!-- End edit ticket -->
 

<script type="text/javascript">
	(function($){
    "use strict";
        $('#clear-files').on('click', function(){
            $('#file_container').html(
                "<div class='form-group'>" +
                    "<label class='col-lg-3 control-label'> <?=lang('attachment')?></label>" +
                    "<div class='col-lg-6'>" +
                    "<input type='file' name='ticketfiles[]'>" +
                    "</div></div>"
            );
        });

        $('#add-new-file').on('click', function(){
            $('#file_container').append(
                "<div class='form-group'>" +
                    "<label class='col-lg-3 control-label'> <?=lang('attachment')?></label>" +
                    "<div class='col-lg-6'>" +
                    "<input type='file' name='ticketfiles[]'>" +
                    "</div></div>"
            );
		});
	})(jQuery);  
  </script>

 


<!-- end -->