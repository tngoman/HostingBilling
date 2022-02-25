	<div class="box">
                <div class="box-header"> 
                
                <a href="<?=base_url()?>sliders/add" data-toggle="ajaxModal" class="btn btn-sm btn-<?=config_item('theme_color');?> pull-right"><?=lang('new_slider')?></a>
                
              </div>
              <div class="box-body">
                <div class="table-responsive">
                  <table id="table-rates" class="table table-striped b-t b-light AppendDataTables">
                    <thead>
                      <tr>
                        <th><?=lang('name')?></th>
                        <th><?=lang('slides')?></th>
                        <th class="col-options no-sort"><?=lang('options')?></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($sliders as $key => $row) { ?>
                      <tr>
                        <td><?=$row->name?></td>
                        <td><?=$row->slides?></td>
                        
                        <td>
                        <a class="btn btn-<?=config_item('theme_color');?> btn-sm" href="<?=base_url()?>sliders/slider/<?=$row->slider_id?>"><?=lang('manage_slides')?></a>
                        <a class="btn btn-warning btn-sm" href="<?=base_url()?>sliders/edit/<?=$row->slider_id?>" data-toggle="ajaxModal" title="<?=lang('edit_slider')?>"><?=lang('edit_slider')?></a>
                        <a class="btn btn-danger btn-sm" href="<?=base_url()?>sliders/delete/<?=$row->slider_id?>" data-toggle="ajaxModal" title="<?=lang('delete_slider')?>"><?=lang('delete_slider')?></a>
                        </td>
                      </tr>
                      <?php }  ?>
                    </tbody>
                  </table>
                </div>
           </div>
      </div>
              
<!-- end -->