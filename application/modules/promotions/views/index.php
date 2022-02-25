<div class="box">
    <div class="box-header font-bold">
        <i class="fa fa-flag"></i> <?=lang('promotions')?>
        <a href="<?=base_url()?>promotions/add_promotion" data-toggle="ajaxModal" class="btn btn-sm btn-<?=config_item('theme_color');?> pull-right"><i class="fa fa-plus"></i> <?=lang('add_promotion')?></a>
        </div>
                <div class="box-body">
                <?php if(isset($response)) {?>
                    <div class="alert alert-info"><?=$response?></div> 
                <?php } ?>
                <div class="table-responsive">
                <table id="table-rates" class="table table-striped b-t b-light AppendDataTables">
                    <thead>
                    <tr>
                        <th><?=lang('code')?></th>
                        <th><?=lang('value')?></th>
                        <th><?=lang('type')?></th>
                        <th><?=lang('start_date')?></th>
                        <th><?=lang('end_date')?></th>
                        <th><?=lang('use_date')?></th>
                        <th><?=lang('description')?></th>
                        <th><?=lang('action')?></th>
                    </tr>
                    </thead>
                    <tbody> 
                    <?php foreach($promotions as $promo)
                    { ?>
                          <tr>
                            <td><?=$promo->code?></td>
                            <td><?=$promo->value?></td>
                            <td><?=($promo->type == 1) ? lang('amount') : lang('percentage') ?></td>
                            <td><?=$promo->start_date?></td>
                            <td><?=$promo->end_date?></td>
                            <td><?=($promo->use_date == 1) ? lang('yes') : lang('no') ?></td>
                            <td><?=$promo->description?></td>
                            <th><a href="<?=base_url()?>promotions/edit/<?=$promo->id?>" class="btn btn-primary btn-xs" data-toggle="ajaxModal">
                                <i class="fa fa-edit"></i> <?=lang('edit')?></a>
                                <a href="<?=base_url()?>promotions/delete/<?=$promo->id?>" class="btn btn-danger btn-xs" data-toggle="ajaxModal">
                                <i class="fa fa-trash-o"></i> <?=lang('delete')?></a>
                            </th>
                        </tr>
                   <?php } ?>                   
                    </tbody>
                </table>  
              </div>                          
        </div>
 </div>
    

<script type="text/javascript">
 $(document).delegate(".datepicker-input", "focusin", function () {
            
            $(this).datepicker().css('z-index','1600');
        });
</script>