               <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs" role="tablist">
                    <li><a class="active" data-toggle="tab" href="#tab-admin"><?=lang('admin')?></a></li>
                    <li><a data-toggle="tab" href="#tab-staff"><?=lang('staff')?></a></li>
                    <li><a data-toggle="tab" href="#tab-client"><?=lang('client')?></a></li>
                </ul>
                <div class="tab-content tab-content-fix">
                    <div class="tab-pane fade in active" id="tab-admin">
                        <div class="table-responsive">
                          <table id="menu-admin" class="table table-striped b-t b-light table-menu sorted_table">
                            <thead>
                                    <tr>
                                    <th></th>
                                    <th class="col-xs-2"><?=lang('icon')?></th>
                                    <th class="col-xs-8"><?=lang('menu')?></th>
                                    <th class="col-xs-2"><?=lang('visible')?></th>
                                    </tr>
                            </thead>
                            <tbody>
                                <?php foreach($admin as $adm) : ?>
                                <tr class="sortable" data-module="<?=$adm->module?>" data-access="1">
                                    <td class="drag-handle"><i class="fa fa-reorder"></i></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-default iconpicker-component" type="button"><i class="fa <?=$adm->icon?> fa-fw"></i></button>
                                            <button data-toggle="dropdown" data-selected="<?=$adm->icon?>" class="menu-icon icp icp-dd btn btn-default dropdown-toggle" type="button" aria-expanded="false" data-role="1" data-href="<?=base_url()?>settings/hook/icon/<?=$adm->module?>">
                                                <span class="caret"></span>
                                            </button>
                                            <div class="dropdown-menu iconpicker-container"></div>
                                        </div>                                        
                                    </td>
                                    <td><?=lang($adm->name)?></td>
                                    <td>
                                        <a data-rel="tooltip" data-original-title="<?=lang('toggle')?>" class="menu-view-toggle btn btn-xs btn-<?=($adm->visible == 1 ? 'success':'default')?>" href="#" data-role="1" data-href="<?=base_url()?>settings/hook/visible/<?=$adm->module?>"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                          </table>
                        </div>
                    </div>
                    <div class="tab-pane fade in" id="tab-staff">
                        <div class="table-responsive">
                          <table id="menu-staff" class="table table-striped b-t b-light table-menu sorted_table">
                            <thead>
                                    <tr>
                                        <th></th>
                                        <th class="col-xs-2"><?=lang('icon')?></th>
                                        <th class="col-xs-3"><?=lang('menu')?></th>
                                        <th class="col-xs-5"><?=lang('permission')?></th>
                                        <th class="col-xs-2"><?=lang('options')?></th>
                                    </tr>
                            </thead>
                            <tbody>
                              <?php foreach($staff as $sta) : ?>
                              <tr class="sortable" data-module="<?=$sta->module?>" data-access="3">
                                  <td class="drag-handle"><i class="fa fa-reorder"></i></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-default iconpicker-component" type="button"><i class="fa <?=$sta->icon?> fa-fw"></i></button>
                                            <button data-toggle="dropdown" data-selected="<?=$sta->icon?>" class="menu-icon icp icp-dd btn btn-default dropdown-toggle" type="button" aria-expanded="false" data-role="3" data-href="<?=base_url()?>settings/hook/icon/<?=$sta->module?>">
                                                <span class="caret"></span>
                                            </button>
                                            <div class="dropdown-menu iconpicker-container"></div>
                                        </div>                                        
                                    </td>
                                    <td><?=lang($sta->name)?></td>
                                    <?php if ($sta->permission != '') { ?>
                                        <td><?=lang('permission_required')?></td>
                                    <?php } else { ?>
                                        <td></td>
                                    <?php }?>
                                        <td>
                                            <a data-rel="tooltip" data-original-title="<?=lang('toggle')?>" class="menu-view-toggle btn btn-xs btn-<?=($sta->visible == 1 ? 'success':'default')?>" href="#" data-role="3" data-href="<?=base_url()?>settings/hook/visible/<?=$sta->module?>"><i class="fa fa-eye"></i></a>
                                        </td>
                              </tr>
                              <?php endforeach; ?>
                            </tbody>
                          </table>
                        </div>
                    </div>
                    <div class="tab-pane fade in" id="tab-client">
                        <div class="table-responsive">
                            <table id="menu-client" class="table table-striped b-t b-light table-menu sorted_table">
                            <thead>
                                    <tr>
                                        <th></th>
                                        <th class="col-xs-2"><?=lang('icon')?></th>
                                        <th class="col-xs-3"><?=lang('menu')?></th>
                                        <th class="col-xs-5"><?=lang('permission')?></th>
                                        <th class="col-xs-2"><?=lang('options')?></th>
                                    </tr>
                            </thead>
                            <tbody>
                                <?php foreach($client as $cli) : ?>
                              <tr class="sortable" data-module="<?=$cli->module?>" data-access="2">
                                  <td class="drag-handle"><i class="fa fa-reorder"></i></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-default iconpicker-component" type="button"><i class="fa <?=$cli->icon?> fa-fw"></i></button>
                                            <button data-toggle="dropdown" data-selected="<?=$cli->icon?>" class="menu-icon icp icp-dd btn btn-default dropdown-toggle" type="button" aria-expanded="false" data-role="2" data-href="<?=base_url()?>settings/hook/icon/<?=$cli->module?>">
                                                <span class="caret"></span>
                                            </button>
                                            <div class="dropdown-menu iconpicker-container"></div>
                                        </div>                                        
                                    </td>
                                    <td><?=lang($cli->name)?></td>
                                    <?php if ($cli->permission != '') { ?>
                                        <td><?=lang('permission_required')?></td>
                                    <?php } else { ?>
                                        <td></td>
                                    <?php }?>
                                        <td>
                                            <a data-rel="tooltip" data-original-title="<?=lang('toggle')?>" class="menu-view-toggle btn btn-xs btn-<?=($cli->visible == 1 ? 'success':'default')?>" href="#" data-role="2" data-href="<?=base_url()?>settings/hook/visible/<?=$cli->module?>"><i class="fa fa-eye"></i></a>
                                        </td>
                              </tr>
                                <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                </div>
              </div>
 