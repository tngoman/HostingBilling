<?php $client = User::is_client();?>       
       <div class="box">
            <div class="box-header">
              <div class="btn-group">

              <button class="btn btn-<?=config_item('theme_color');?> btn-sm">
              <?php
              $view = isset($_GET['view']) ? $_GET['view'] : NULL;
              switch ($view) {
                case 'paid':
                  echo lang('paid');
                  break;
                case 'unpaid':
                  echo lang('not_paid');
                  break;
                case 'partially_paid':
                  echo lang('partially_paid');
                  break;
                case 'recurring':
                  echo lang('recurring');
                  break;

                default:
                  echo lang('filter');
                  break;
              }
              ?></button>
              <button class="btn btn-<?=config_item('theme_color');?> btn-sm dropdown-toggle" data-toggle="dropdown"><span class="caret"></span>
              </button>
              <ul class="dropdown-menu">

              <li><a href="<?=base_url()?>invoices?view=paid"><?=lang('paid')?></a></li>
              <li><a href="<?=base_url()?>invoices?view=unpaid"><?=lang('not_paid')?></a></li>
              <li><a href="<?=base_url()?>invoices?view=partially_paid"><?=lang('partially_paid')?></a></li>
              <li><a href="<?=base_url()?>invoices?view=recurring"><?=lang('recurring')?></a></li>
              <li><a href="<?=base_url()?>invoices"><?=lang('all_invoices')?></a></li>

              </ul>
              </div>
              
              <?php
              if(User::is_admin() || User::perm_allowed(User::get_id(),'add_invoices')) { ?>
              <a href="<?=base_url()?>invoices/add" class="btn btn-sm btn-<?=config_item('theme_color');?> pull-right"><i class="fa fa-plus"></i> <?=lang('create_invoice')?></a>
              <?php } ?>
              </div>
            <div class="box-body">
            <div class="table-responsive">
		      <table class="table table-striped b-t AppendDataTables">
                <thead>
                  <tr>
                    <th class="w_5 hidden"></th>
                    <th class=""><?=lang('invoice')?></th>
                    <th class=""><?=lang('client_name')?></th>
                    <th class=""><?=lang('status')?></th>
                    <th class="col-date"><?=lang('date')?></th>
                    <th class="col-date"><?=lang('due_date')?></th>
                    <th class="col-currency"><?=lang('sub_total')?></th>
                    <th class="col-currency"><?=lang('due_amount')?></th>
                    <th><?=lang('options')?></th>

                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($invoices as $key => &$inv) {
                    $status = Invoice::payment_status($inv->inv_id);
                    switch ($status) {
                        case 'fully_paid': $label2 = 'success';  break;
                        case 'partially_paid': $label2 = 'warning'; break;
                        case 'not_paid': $label2 = 'danger'; break;
                        case 'cancelled': $label2 = 'primary'; break;
                    }
                  ?>
                  <tr class="<?=($inv->status == 'Cancelled') ? 'text-danger' : '';?>">
                  <td class="hidden"><?=$inv->inv_id?></td>

                  <td style="border-left: 2px solid <?php echo ($status == 'fully_paid') ? '#1ab394' :'#f0ad4e'; ?>"> 
                    <a class="text-info" href="<?=base_url()?>invoices/view/<?=$inv->inv_id?>">
                    <?=$inv->reference_no?>
                    </a>

                    </td>

                    <td>
                    <?php 
                    $client = Client::view_by_id($inv->client);
                    echo is_object($client) ? Client::view_by_id($inv->client)->company_name : '';?>
                    </td>

                    <td class="">
                        <span class="label label-<?=$label2?>"><?=lang($status)?> <?php if($inv->emailed == 'Yes') { ?><i class="fa fa-envelope-o"></i><?php } ?></span>
                      <?php if ($inv->recurring == 'Yes') { ?>
                      <span class="label label-primary"><i class="fa fa-retweet"></i></span>
                      <?php }  ?>

                    </td>

                    <td><?=strftime(config_item('date_format'), strtotime($inv->date_saved))?></td>

                    <td><?=strftime(config_item('date_format'), strtotime($inv->due_date))?></td>

                    <td class="col-currency">
                      <?php if($client) {
                        $client_cur = Client::view_by_id($inv->client)->currency;
                        echo Applib::format_currency($client_cur, Applib::client_currency($client_cur, Invoice::get_invoice_subtotal($inv->inv_id)));
                      }
                      else{
                        echo Applib::format_currency($inv->currency, Invoice::get_invoice_subtotal($inv->inv_id));
                      } ?>
                    </td>

                    <td class="col-currency">
                     <?php if($client) {
                        $client_cur = Client::view_by_id($inv->client)->currency;
                        echo Applib::format_currency($client_cur, Applib::client_currency($client_cur, Invoice::get_invoice_due_amount($inv->inv_id)));
                      }
                      else{
                        echo Applib::format_currency($inv->currency, Invoice::get_invoice_due_amount($inv->inv_id));
                      } ?>
                    
                    </td>
                    <td>

                      <div class="">
                   
                           <a class="btn btn-xs btn-primary" href="<?=base_url()?>invoices/view/<?=$inv->inv_id?>" 
                           data-toggle="tooltip" data-original-title="<?= lang('view') ?>" data-placement="top">
                           <i class="fa fa-eye"></i>
                           </a>
                          

                <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_invoices')) { ?>
                         
                          <a class="btn btn-xs btn-twitter" href="<?=base_url()?>invoices/edit/<?=$inv->inv_id?>" 
                          data-toggle="tooltip" data-original-title="<?= lang('edit') ?>" data-placement="top">
                          <i class="fa fa-pencil"></i>
                          </a>                          

                <?php } ?>
                          
                        <a class="btn btn-xs btn-warning" href="<?=base_url()?>invoices/transactions/<?=$inv->inv_id?>" 
                        data-toggle="tooltip" data-original-title="<?= lang('payments') ?>" data-placement="top">
                        <i class="fa fa-money"></i></a>
                          

                <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'email_invoices')) { ?>
                        <a class="btn btn-xs btn-success" href="<?=base_url()?>invoices/send_invoice/<?=$inv->inv_id?>" 
                        data-toggle="ajaxModal"><span data-toggle="tooltip" data-original-title="<?=lang('email_invoice')?>" data-placement="top">
                        <i class="fa fa-envelope"></i></span></a>
                <?php } ?>


                <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'send_email_reminders')) : ?>                   
                    <a href="<?=base_url()?>invoices/remind/<?=$inv->inv_id?>" data-toggle="ajaxModal" 
                    class="btn btn-xs btn-vk" data-original-title="<?=lang('send_reminder')?>">
                    <span data-toggle="tooltip" data-original-title="<?=lang('send_reminder')?>" data-placement="top">
                    <i class="fa fa-bell"></i></span> </a>
                <?php endif; ?>
                    
                      <a class="btn btn-xs btn-linkedin" href="<?=base_url()?>fopdf/invoice/<?=$inv->inv_id?>" 
                      data-toggle="tooltip" data-original-title="<?=lang('pdf') ?>" data-placement="top">
                      <i class="fa fa-file-pdf-o"></i></a>

                <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'delete')) { ?>
                                 
                      <a class="btn btn-xs btn-danger" href="<?= base_url() ?>invoices/delete/<?= $inv->inv_id ?>" data-toggle="ajaxModal">
                      <span data-toggle="tooltip" data-original-title="<?=lang('delete')?>" data-placement="top"><i class="fa fa-trash"></i></span></a>
                        

                <?php } ?>

                </div>
                     </td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
        </div>
    </div>
  