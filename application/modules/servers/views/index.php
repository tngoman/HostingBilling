<?php
$this->db->select('*'); 
$this->db->from('servers');
$servers = $this->db->get()->result(); 
?>


<div class="box">
    <div class="box-header font-bold">
        <i class="fa fa-server"></i> <?=lang('servers')?>
        <a href="<?=base_url()?>servers/add_server" data-toggle="ajaxModal" class="btn btn-sm btn-<?=config_item('theme_color');?> pull-right"><i class="fa fa-plus"></i> <?=lang('add_server')?></a>
        </div>
                <div class="box-body">
                <?php if(isset($response)) {?>
                    <div class="alert alert-info"><?=$response?></div> 
                <?php } ?>
                <div class="table-responsive">
                <table id="table-rates" class="table table-striped b-t b-light AppendDataTables">
                    <thead>
                    <tr> 
                        <th><?=lang('server_name')?></th>
                        <th><?=lang('server_host')?></th>
                        <th><?=lang('port')?></th>
                        <th><?=lang('default')?></th>
                        <th class="col-options no-sort"><?=lang('options')?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($servers as $key => $r) { ?>
                    <tr> 
                        <td><?=$r->name?></td>
                        <td><?=$r->hostname?></td>
                        <td><?=$r->port?></td> 
                        <td><?=($r->selected == 1) ? '<i class="fa fa-check"></i>' : '' ?></td>                       
                        <td>
                        <?= modules::run($r->type.'/admin_options', $r)?> 
                        </td>
                    </tr>
                    <?php }  ?>
                    </tbody>
                </table>  
              </div>                          
        </div>
 </div>
    