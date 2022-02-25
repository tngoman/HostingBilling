    <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'view_all_invoices')) { ?>
    <table id="table-invoices" class="table table-striped b-t b-light AppendDataTables">
        <thead>
        <tr>
            <th class="w_5 hidden"></th>
            <th><?=lang('invoice')?></th>
            <th class=""><?=lang('status')?></th>
            <th class="col-date"><?=lang('due_date')?></th>
            <th class="col-currency"><?=lang('amount')?></th>
            <th class="col-currency"><?=lang('due_amount')?></th>
            <th><?=lang('options')?></th>
        </tr> </thead> <tbody>
<?php foreach (Invoice::get_client_invoices($company) as $key => $inv) { ?>
    <?php
    $status = Invoice::payment_status($inv->inv_id);
    switch ($status) {
        case 'fully_paid': $label2 = 'success';  break;
        case 'partially_paid': $label2 = 'warning'; break;
        case 'not_paid': $label2 = 'danger'; break;
    } ?>
            <tr>
                <td class="hidden"><?=$inv->inv_id?></td>
                <td>
                    <a class="text-info" href="<?=base_url()?>invoices/view/<?=$inv->inv_id?>">
                        <?=$inv->reference_no?>
                    </a>
                </td>

                <td class="">
                    <span class="label label-<?=$label2?>"><?=lang($status)?> <?php if($inv->emailed == 'Yes') { ?><i class="fa fa-envelope-o"></i><?php } ?></span>
                  <?php if ($inv->recurring == 'Yes') { ?>
                  <span class="label label-primary"><i class="fa fa-retweet"></i></span>
                  <?php }  ?>

                </td>

                <td><?=strftime(config_item('date_format'), strtotime($inv->due_date))?></td>
                <td class="col-currency">
                <?=Applib::format_currency($inv->currency, Invoice::get_invoice_subtotal($inv->inv_id))?>
                </td>

                <td class="col-currency">
                    <strong>
                <?=Applib::format_currency($inv->currency, Invoice::get_invoice_due_amount($inv->inv_id));?>
                </strong>
                </td>

                <td> 
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
 
                </td>
            </tr>
        <?php  } ?>



        </tbody>
    </table>

    <?php } ?>
 