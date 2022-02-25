<div class="box">
        <div class="box-header b-b b-light">
          <a href="<?=base_url()?>companies/create" class="btn btn-<?=config_item('theme_color');?> btn-sm pull-right" data-toggle="ajaxModal" title="<?=lang('new_company')?>" data-placement="bottom"><i class="fa fa-plus"></i> <?=lang('new_client')?></a>
          <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts')) { ?>
          <a href="<?=base_url()?>companies/upload" class="btn btn-info btn-sm" title="<?=lang('new_company')?>" data-placement="bottom"><i class="fa fa-download"></i> <?=lang('import_whmcs')?></a>
          <?php } ?>
        </div>
      
              <div class="box-body">
                <div class="table-responsive">
                  <table id="table-clients" class="table table-striped m-b-none AppendDataTables">
                    <thead>
                      <tr>
                        
                        <th><?=lang('client')?> </th>
                        <th><?=lang('company_id')?> </th>
                        <th><?=lang('credit_balance')?></th>
                        <th><?=lang('due_amount')?></th>
                        <th class="hidden-sm"><?=lang('primary_contact')?></th>
                        <th><?=lang('email')?> </th>
                        <th class="col-options no-sort"><?=lang('options')?> </th>
                      </tr> </thead> <tbody>
                      <?php
                      if (!empty($companies)) {
                      foreach ($companies as $client) { 
                        $client_due = Client::due_amount($client->co_id);
                        ?>
                      <tr>
                        <td>
                        <i class="fa fa-briefcase text-<?=($client_due > 0) ? 'default': 'success'; ?>"></i>

                        <a href="<?=base_url()?>companies/view/<?=$client->co_id?>" class="text-info">
                        <?=($client->company_name != NULL) ? $client->company_name : '...'; ?></a></td>

                        <td> 
                        <?=$client->company_ref?> 
                        </td>

                        <td>
                        <strong>
                        <?=Applib::format_currency(config_item('default_currency'), $client->transaction_value)?>
                          </strong>
                        </td>


                        <td>
                        <strong>
                        <?=Applib::format_currency(config_item('default_currency'), $client_due)?>
                          </strong>
                        </td>

      
                        <td class="hidden-sm">
                        <?php if ($client->individual == 0) { 
                          echo ($client->primary_contact) ? User::displayName($client->primary_contact) : 'N/A'; 
                          } ?>
                        </td>



                      <td><?=$client->company_email?></td>
                      <td>

                      <a href="<?=base_url()?>companies/view/<?=$client->co_id?>" class="btn btn-success btn-xs" title="<?=lang('view')?>"><i class="fa fa-eye"></i> <?=lang('view')?></a>

                        <a href="<?=base_url()?>companies/delete/<?=$client->co_id?>" class="btn btn-danger btn-xs" data-toggle="ajaxModal" title="<?=lang('delete')?>"><i class="fa fa-trash-o"></i> <?=lang('delete')?></a>
                        
                      </td>
                    </tr>
                    <?php } } ?>
                    
                    
                  </tbody>
                </table>

              </div>          
          </div>
        </div>
     