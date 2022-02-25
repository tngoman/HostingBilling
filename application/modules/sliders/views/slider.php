	<div class="box">
                <div class="box-header"> 
                
                <a href="<?=base_url()?>sliders/add_slide/<?=$slider_id?>" data-toggle="ajaxModal" class="btn btn-sm btn-<?=config_item('theme_color');?> pull-right"><?=lang('new_slide')?></a>
                
              </div>
              <div class="box-body">
                <div class="table-responsive">
                  <table id="table-rates" class="table table-striped b-t b-light AppendDataTables">
                    <thead>
                      <tr>
                        <th><?=lang('image')?></th>
                        <th><?=lang('title')?></th>
                        <th><?=lang('description')?></th>
                        <th class="col-options no-sort"><?=lang('options')?></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($slider as $key => $row) { ?>
                      <tr>
                        <td>
                        <?php if(!empty($row->image)) {?>
                        <img src="<?=base_url()?>resource/uploads/<?=$row->image?>" class="list_thumb" />
                        <?php } ?>
                        </td>
                        <td><?=$row->title?></td>
                        <td><?=$row->description?></td>
                        <td>
                        <a class="btn btn-warning btn-sm" href="<?=base_url()?>sliders/edit_slide/<?=$row->slide_id?>" data-toggle="ajaxModal" title="<?=lang('edit_slide')?>"><?=lang('edit_slide')?></a>
                        <a class="btn btn-danger btn-sm" href="<?=base_url()?>sliders/delete_slide/<?=$row->slide_id?>" data-toggle="ajaxModal" title="<?=lang('delete_slide')?>"><?=lang('delete_slide')?></a>
                        </td>
                      </tr>
                      <?php }  ?>
                    </tbody>
                  </table>
                </div>
           </div>
      </div>
              
<!-- end -->