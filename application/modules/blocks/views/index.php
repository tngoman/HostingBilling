<div class="box">
                <div class="box-header"> 
                
                <a href="<?=base_url()?>blocks/add" class="btn btn-sm btn-<?=config_item('theme_color');?> pull-right"><?=lang('new_block')?></a>
                
              </div>
              <div class="box-body">
                <div class="table-responsive">
                  <table id="table-rates" class="table table-striped b-t b-light AppendDataTables">
                    <thead>
                      <tr>
                        <th><?=lang('name')?></th>
                        <th><?=lang('module')?></th>
                        <th><?=lang('type')?></th>
                        <th><?=lang('section')?></th>
                        <th class="col-options no-sort"><?=lang('options')?></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($blocks as $key => $row) { ?>
                      <tr>
                        <td><?=$row->name?></td>
                        <td><?=$row->module?></td>
                        <td><span class="label <?=($row->type == 'Module') ? 'label-primary' : 'label-warning'?>"><?=$row->type?></span></td>
                        <td>
                          <?php 
                            foreach($sections as $section) {
                              if(!isset($row->param)) {
                                if($section->id == $row->id) {
                                    echo $section->section;                              
                                }
                              }
                              else {
                                if($section->id == $row->param) {
                                  echo $section->section;                              
                                }
                              }
                            }
                          ?>
                        </td>
                        <td>
                          <?php 
                              
                              $id = '';

                              $parts = $row->id;
                              $part = explode('_', $parts);
                              if($row->type == 'Custom') 
                              {                             
                                  $id = strtolower($row->module)."_".$row->id;
                              } 
 
                              if($row->type == 'Module' && isset($row->param)) { 
                                  $id = $row->param;
                              }

                              if($row->type == 'Module' && count($part) > 1 && isset($part[1]) && !is_numeric($part[1])) 
                              {
                                   $id = $row->id;
                              }
                                  
                            ?>

                          <a data-toggle="ajaxModal" class="btn btn-primary btn-sm" href="<?=base_url()?>blocks/configure/<?=$id?>"><?=lang('configure')?></a>
                          <<?=($row->type == 'Module') ? 'button disabled' : 'a'; ?> class="btn btn-warning btn-sm" href="<?=base_url()?>blocks/edit/<?=$row->id?>" title="<?=lang('edit')?>"><?=lang('edit')?><?=($row->type == 'Module') ? '</button>' : '</a>'; ?>
                          <<?=($row->type == 'Module') ? 'button disabled' : 'a'; ?> data-toggle="ajaxModal" class="btn btn-danger btn-sm" href="<?=base_url()?>blocks/delete/<?=$row->id?>" title="<?=lang('delete')?>"><?=lang('delete')?><?=($row->type == 'Module') ? '</button>' : '</a>'; ?>
                        </td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
           </div>
      </div>
              
<!-- end -->