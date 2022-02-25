      <div class="box">
          <div class="box-body">
                <div class="table-responsive">
                  <table id="table-payments" class="table table-striped b-t b-light AppendDataTables">
                    <thead>
                      <tr>
                        <th class="w_5 hidden"></th>
                        <th class=""><?=lang('invoice')?></th>
                        <th class=""><?=lang('client')?></th>
                        <th class="col-date"><?=lang('payment_date')?></th>
                        <th class="col-date"><?=lang('invoice_date')?></th>
                        <th class="col-currency"><?=lang('amount')?></th>
                        <th class=""><?=lang('payment_method')?></th>
                        <th class=""><?=lang('options')?></th>
                      </tr>
                    </thead>
                    <tbody>


                    <?php foreach ($payments as $key => $p) { ?>
                      <tr>
                      <?php
                        $currency = Invoice::view_by_id($p->invoice)->currency;
                        $invoice_date = Invoice::view_by_id($p->invoice)->date_saved;
                        $invoice_date = strftime(config_item('date_format'), strtotime($invoice_date));
                        ?>


                        <td class="hidden"><?=$p->p_id?></td>

                        <td style="border-left: 2px solid <?=($p->refunded != 'Yes') ? '#1AB394':'#e05d6f'; ?>;">
                         
                        <a class="text-info" href="<?=base_url()?>payments/view/<?=$p->p_id?>">
                        <?php echo Invoice::view_by_id($p->invoice)->reference_no; ?></a>
                        </td>

                        <td>
                        <?php echo Client::view_by_id($p->paid_by)->company_name; ?>
                        </td>
                        <td><?=strftime(config_item('date_format'), strtotime($p->payment_date));?></td>
                        <td><?=$invoice_date?></td>

                        <td class="col-currency <?=($p->refunded == 'Yes') ? 'text-lt text-danger' : '' ; ?>">
                        <strong>
                        <?=Applib::format_currency($currency, $p->amount)?>
                        </strong>
                        </td>

                        <td><?php echo App::get_method_by_id($p->payment_method); ?></td>

                        <td>                        
                            <a class="btn btn-xs btn-primary" href="<?=base_url()?>payments/view/<?=$p->p_id?>" data-toggle="tooltip" data-original-title="<?=lang('view_payment')?>" data-placement="top"><i class="fa fa-eye"></i></a>
                            
                            <a class="btn btn-xs btn-warning" href="<?=base_url()?>payments/pdf/<?=$p->p_id?>" data-toggle="tooltip" data-original-title="<?=lang('pdf')?> <?=lang('receipt')?>" data-placement="top"><i class="fa fa-file-pdf-o"></i></a>
                             

                            <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'edit_payments')){ ?>
                            <a class="btn btn-xs btn-success" data-toggle="tooltip" data-original-title="<?=lang('edit_payment')?>" data-placement="top" href="<?=base_url()?>payments/edit/<?=$p->p_id?>"><i class="fa fa-pencil"></i></a> 
                            <?php if($p->refunded == 'No'){ ?>
                             <span data-toggle="tooltip" data-original-title="<?=lang('refund')?>" data-placement="top"><a class="btn btn-xs btn-twitter" href="<?=base_url()?>payments/refund/<?=$p->p_id?>" data-toggle="ajaxModal"><i class="fa fa-warning"></i></a></span>
                            
                            <?php } } ?>
                             <a class="btn btn-xs btn-google" href="<?=base_url()?>payments/delete/<?=$p->p_id?>" data-toggle="ajaxModal"><i class="fa fa-trash"></i></a>
                            
                            </td>
                      </tr>
                      <?php }  ?>
                    </tbody>
                  </table>
                </div>
          </div>
   </div>
 

<!-- end -->