
            <div class="box">
                <?php $inv = Invoice::view_by_id($id); ?>
                <div class="box-header b-b clearfix hidden-print">              
                    
                        <strong><?=lang('invoice')?> <?=$inv->reference_no?></strong>
                            <div class="btn-group pull-right">
                            <a href="<?=site_url()?>invoices/view/<?=$inv->inv_id?>" class="btn btn-sm btn-success">
                                <?=lang('view_invoice')?>
                            </a>


                            <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_invoices')) { ?>

                            <?php } ?>

                            <?php if (Invoice::payment_status($inv->inv_id) != 'fully_paid') : ?>

                              
                            <?php endif; ?>
 

                                    <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'email_invoices')) { ?>
                                      
                                            <a class="btn btn-sm btn-vk" href="<?= base_url() ?>invoices/send_invoice/<?= $inv->inv_id ?>" data-toggle="ajaxModal" title="<?= lang('email_invoice') ?>"><?= lang('email_invoice') ?></a>
                                        
                                    <?php } if (User::is_admin() || User::perm_allowed(User::get_id(),'send_email_reminders')) { ?>
                                       
                                        <a class="btn btn-sm btn-google" href="<?= base_url() ?>invoices/remind/<?= $inv->inv_id ?>" data-toggle="ajaxModal" title="<?= lang('send_reminder') ?>"><?= lang('send_reminder') ?></a>
                                        
                                    <?php } ?>
  
                         
                            <?php if (config_item('pdf_engine') == 'invoicr') { ?>
                                <a href="<?= base_url() ?>fopdf/invoice/<?=$inv->inv_id ?>" class="btn btn-sm btn-primary"><i class="fa fa-file-pdf-o"></i> <?=lang('pdf') ?></a>
                            <?php } ?>

                            </div>
                        </div>

                        <div class="box-body">      
                                                           
                                <div class="table-responsive">
                                    <table id="table-payments" class="table table-striped b-t b-light AppendDataTables">
                                        <thead>
                                        <tr>
                                            <th class="col-options no-sort  col-sm-2"><?=lang('trans_id')?></th>
                                            <th class="col-sm-3"><?=lang('client')?></th>
                                            <th class="col-date col-sm-2"><?=lang('payment_date')?></th>
                                            <th class="col-currency col-sm-2"><?=lang('amount')?></th>
                                            <th class="col-sm-2"><?=lang('payment_method')?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($payments as $key => $p) { ?>


                                            <tr>

                                                <td>

                                                    <a href="<?=base_url()?>payments/view/<?=$p->p_id?>" class="small text-info">
                                                        <?=$p->trans_id?>
                                                    </a>
                                                </td>


                                                <td>
                                                    <?php echo Client::view_by_id($p->paid_by)->company_name; ?>
                                                </td>


                                                <td><?=strftime(config_item('date_format'), strtotime($p->payment_date));?></td>


                                                <td class="col-currency"><?=Applib::format_currency($inv->currency, $p->amount)?></td>


                                                <td><?php echo App::get_method_by_id($p->payment_method); ?>
                                                </td>


                                            </tr>


                                        <?php } ?>


                                        </tbody>
                                    </table>
                                </div> 
                    
                  </div>
 
