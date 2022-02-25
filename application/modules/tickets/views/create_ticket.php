
<!-- Start -->
<div class="box">
	<div class="box-body">
	<?php echo $this->session->flashdata('form_error'); ?>

	<?php if(isset($_GET['dept'])){
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open_multipart(base_url().'tickets/add/?dept='.$_GET['dept'],$attributes);
           ?>

			 <input type="hidden" name="department" value="<?=$_GET['dept']?>">

			    <div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('ticket_code')?> <span class="text-danger">*</span></label>
				<div class="col-lg-3">
					<input type="text" class="form-control w_260" value="<?php echo Ticket::generate_code(); ?>" name="ticket_code" readonly="readonly">
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('subject')?> <span class="text-danger">*</span></label>
				<div class="col-lg-7">
					<input type="text" class="form-control w_260" placeholder="<?=lang('sample_ticket_subject')?>" name="subject" required>
				</div>
				</div>
				<?php if (User::is_admin() || User::is_staff()) { ?>

				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('reporter')?> <span class="text-danger">*</span> </label>
				<div class="col-lg-6">
					<div class="m-b">
					<select class="select2-option w_260" name="reporter" required>
					<optgroup label="<?=lang('users')?>">
					<?php foreach (User::all_users() as $u): ?>
					<option value="<?=$u->id?>"><?=User::displayName($u->id);?></option>
					<?php endforeach; ?>
					</optgroup>
					</select>
					</div>
				</div>
			</div>
			<?php } ?>



				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('priority')?> <span class="text-danger">*</span> </label>
				<div class="col-lg-6">
					<div class="m-b">
					<select name="priority" class="form-control w_260">
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
				<label class="col-lg-3 control-label"><?=lang('ticket_message')?> </label>
				<div class="col-lg-9">
				<textarea name="body" class="form-control textarea" placeholder="<?=lang('message')?>"><?php echo set_value('body');?></textarea>

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




			<?php
			$dept = isset($_GET['dept']) ? $_GET['dept'] : 0;
		$additional = $this->db->where(array('deptid'=> $dept))->get("fields")->result_array();
if (is_array($additional) && !empty($additional))
{
	foreach ($additional as $item)
	{
		$label = ($item['label'] == NULL) ? $item['name'] : $item['label'];
		echo '<div class="form-group">';
		echo ' <label class="col-lg-3 control-label"> ' .$label. '</label>';
		echo ' <div class="col-lg-3">';
		if ($item['type'] == 'text')
		{
			echo ' <input type="text" class="form-control" name="' . $item['uniqid'] . '">  ';
		}
		echo ' </div>';
		echo ' </div>';
	}


}
?>

<div class="line line-dashed line-lg pull-in"></div>

<button type="submit" class="btn btn-sm btn-<?=config_item('theme_color')?>"><i class="fa fa-ticket"></i>
<?=lang('create_ticket')?></button>



		</form>

		<?php }else{
			$attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'tickets/add',$attributes); ?>

          <div class="form-group">
				<label class="col-lg-2 control-label"><?=lang('department')?> <span class="text-danger">*</span> </label>
				<div class="col-lg-6">
					<div class="m-b">
					<select name="dept" class="form-control" required>
					<?php
					$departments = App::get_by_where('departments',array('deptid >'=>'0'));
					foreach ($departments as $d): ?>
					<option value="<?=$d->deptid?>"><?=strtoupper($d->deptname)?></option>
					<?php endforeach; ?>
					</select>
					</div>
				</div>

				<?php if (User::is_admin()) { ?>
				<a href="<?=base_url()?>settings/?settings=departments" class="btn btn-danger btn-sm" data-toggle="tooltip" title="<?=lang('departments')?>"><i class="fa fa-plus"></i> <?=lang('departments')?></a>
				<?php } ?>


			</div>
<button type="submit" class="btn btn-sm btn-<?=config_item('theme_color')?>"><?=lang('select_department')?></button>

</form>
		<?php } ?>
</div>
</div>


<!-- End create ticket -->


					</section>


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
