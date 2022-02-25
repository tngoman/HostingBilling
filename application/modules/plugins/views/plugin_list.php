<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="box">
        <div class="box-header">
        <i class="fa fa-server"></i> <?=lang('plugins')?>        
            </div>
            <div class="box-body">
                <table id="table-templates-2" class="table table-striped b-t b-light text-sm AppendDataTables dataTable no-footer">
                    <thead>
                        <tr>
                            <th><?=lang('plugin')?></th>
                            <th><?=lang('category')?></th>
                            <th><?=lang('status')?></th>
                            <th><?=lang('uri')?></th>
                            <th><?=lang('version')?></th>
                            <th><?=lang('description')?></th>
                            <th><?=lang('author')?></th> 
                            <th><?=lang('options')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($plugins as $k => $p): ?>
                        <tr>
                            <td><?php echo $p->name; ?></td>
                            <td><?php echo $p->category; ?></td>
                            <td><?= ($p->status ? 'Enabled' : 'Disabled'); ?></td>
                            <td><?='<a href=' . $p->uri . '" target="_blank">' . $p->uri . '</a>'; ?></td>
                            <td><?= $p->version; ?></td>
                            <td><?= $p->description; ?></td>
                            <td><?='<a href="http://' . $p->author_uri . '" target="_blank">' . $p->author . '</a>'; ?></td> 
                            <td> 
                            <?php if ($p->status == 1) { ?>
                            <a class="btn btn-primary btn-sm trigger" href="<?= site_url('plugins/config?plugin=' . $p->system_name) ?>" data-toggle="ajaxModal"><?=lang('settings')?></a> 
                            <?php } else { ?>
                                <a class="btn btn-warning btn-sm trigger" href="<?= site_url('plugins/uninstall/' . $p->system_name) ?>" data-toggle="ajaxModal"><?=lang('uninstall')?></a> 
                            <?php } if ($p->status == 0) { ?><a
                                class="btn btn-success btn-sm" href="<?= site_url('plugins/activate/' . $p->system_name) ?>">
                                <?=lang('activate')?></a><?php } else { ?>
                                <a class="btn btn-warning btn-sm" href="<?= site_url('plugins/deactivate/' . $p->system_name) ?>" href="<?php echo site_url('plugin/deactivate/' . $p->system_name) ?>">
                                <?=lang('deactivate')?></a><?php } ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

