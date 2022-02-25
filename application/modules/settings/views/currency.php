<div class="row">
    <!-- Start Form -->
<div class="col-lg-12">

<?php
        $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open_multipart('settings/xrates', $attributes); ?>

    <div class="form-group">
        <label class="col-lg-4 control-label"><?=lang('xrates_app_id')?></label>
        <div class="col-lg-5">                                   
            <input type="text" name="xrates_app_id" class="form-control" value="<?=config_item('xrates_app_id')?>">            
        </div>

        <div class="col-lg-3">                                   
        <small><a target="_blank" class="" href="https://openexchangerates.org/signup/free"><?=lang('get_api_key')?></a></small>
        </div>
 

    </div> 

    <div class="text-center">
                            <button type="submit" class="btn btn-sm btn-<?=config_item('theme_color');?>"><?=lang('save_changes')?></button>
                        </div>
                    
    </form>




<div class="table-responsive"> 

<a href="<?=base_url()?>settings/add_currency" data-toggle="ajaxModal" title="<?=lang('add_currency')?>" class="btn btn-twitter btn-sm"><?=lang('add_currency')?></a>
<hr>

 <div class="alert alert-info small">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong>Notice</strong> Rates based on United States Dollar (USD)
    </div>

<table class="table table-striped b-t b-light"> 
<thead> 
<tr> 

<th class="th-sortable" data-toggle="class">Code</th> 
<th>Code Name</th> 
<th>Symbol</th> 
<th>xChange Rate</th> 
<th width="30"></th> 
</tr> 
</thead> 
<tbody> 
<?php $currencies = $this->db->get('currencies')->result();
foreach ($currencies as $key => $cur) { ?>
<tr> 
<td><?=$cur->code?></td> 
<td><?=$cur->name?></td> 
<td><?=$cur->symbol?></td> 
<td><?=$cur->xrate?></td> 
<td> 
<a href="<?=base_url()?>settings/edit_currency/<?=$cur->code?>" data-toggle="ajaxModal" data-placement="left" title="<?=lang('edit_currency')?>">
<i class="fa fa-edit text-success"></i>
</a> 
</td> 
</tr> 
   
 <?php } ?> 
</tbody> 
</table> 
</div>



  

    </div>
    <!-- End Form -->
</div>
