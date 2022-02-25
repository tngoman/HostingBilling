	<div class="box">
                <div class="box-header"> 
                
                <a href="<?=base_url()?>invoices/tax_rates/add" data-toggle="ajaxModal" class="btn btn-sm btn-<?=config_item('theme_color');?> pull-right"><?=lang('new_tax_rate')?></a>
                
              </div>
              <div class="box-body">
                <div class="table-responsive">
                  <table id="table-rates" class="table table-striped b-t b-light AppendDataTables">
                    <thead>
                      <tr>
                        <th><?=lang('tax_rate_name')?></th>
                        <th><?=lang('tax_rate_percent')?></th>
                        <th class="col-options no-sort"><?=lang('options')?></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rates as $key => $r) { ?>
                      <tr>
                        <td><?=$r->tax_rate_name?></td>
                        <td><?=$r->tax_rate_percent?> %</td>
                        
                        <td>
                        <a class="btn btn-<?=config_item('theme_color');?> btn-sm" href="<?=base_url()?>invoices/tax_rates/edit/<?=$r->tax_rate_id?>" data-toggle="ajaxModal" title="<?=lang('edit_rate')?>"><?=lang('edit_rate')?></a>
                <a class="btn btn-dark btn-sm" href="<?=base_url()?>invoices/tax_rates/delete/<?=$r->tax_rate_id?>" data-toggle="ajaxModal" title="<?=lang('delete_rate')?>"><?=lang('delete_rate')?></a>
                        </td>
                      </tr>
                      <?php }  ?>
                    </tbody>
                  </table>
                </div>
           </div>
      </div>
              
<!-- end -->