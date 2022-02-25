 <?php
if($this->session->flashdata('message')){ ?>
<?php if ($this->session->flashdata('response_status') == 'success') { $alert_type = 'success'; }else{ $alert_type = 'danger'; } ?>
<div class="alert alert-<?=$alert_type?>"> 
<button type="button" class="close" data-dismiss="alert">×</button> <i class="fa fa-info-sign"></i>
<?=$this->session->flashdata('message');?>
</div>

    <?php } ?> 