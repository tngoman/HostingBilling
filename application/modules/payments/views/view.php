          <div class="box">
            <div class="box-header clearfix">               
                <?php $i = Payment::view_by_id($id); ?>
                <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'edit_payments')){ ?>

                    <a href="<?=base_url()?>payments/edit/<?=$i->p_id?>" title="<?=lang('edit_payment')?>" class="btn btn-sm btn-<?=config_item('theme_color');?>">
                  <i class="fa fa-pencil text-white"></i> <?=lang('edit_payment')?></a>

                  <?php if($i->refunded == 'No'){ ?>
                  <a href="<?=base_url()?>payments/refund/<?=$i->p_id?>" title="<?=lang('refund')?>" class="btn btn-sm btn-<?=config_item('theme_color');?>" data-toggle="ajaxModal">
                  <i class="fa fa-warning text-white"></i> <?=lang('refund')?></a>
                  <?php } ?>

                  <?php } ?>

                  <a href="<?=base_url()?>payments/pdf/<?=$i->p_id?>" title="<?=lang('pdf')?>" class="btn btn-sm btn-<?=config_item('theme_color');?>">
                  <i class="fa fa-file-pdf-o text-white"></i> <?=lang('pdf')?> <?=lang('receipt')?></a>
            </div> 
            
            <div class="box-body">
      
              <!-- Start Payment -->
              <?php if($i->refunded == 'Yes') { ?>
              <div class="alert alert-danger hidden-print">
              <button type="button" class="close" data-dismiss="alert">×</button>
              <i class="fa fa-warning"></i> <?=lang('transaction_refunded')?>
              </div>
              <?php } ?>


              <div class="column content-column">
                <div class="details-page">
                  <div class="details-container clearfix mb_20" id="payment_view">
                    <div class="row">
                      <div class="col-md-6">
                          <table class="table">
                            <tbody>
                              <tr><td class="line_label"><?=lang('payment_date')?></td><td><?=strftime(config_item('date_format')." %H:%M:%S", strtotime($i->created_date));?></td></tr>
                              <tr><td class="line_label"><?=lang('transaction_id')?></td><td><?=$i->trans_id?></td></tr>
                              <tr><td class="line_label"><?=lang('received_from')?></td><td><strong><a href="<?=base_url()?>companies/view/<?=$i->paid_by?>">
                          <?=ucfirst(Client::view_by_id($i->paid_by)->company_name);?></a></strong></td></tr>
                              <tr><td class="line_label"><?=lang('payment_mode')?></td><td><?=App::get_method_by_id($i->payment_method)?></td></tr>
                              <tr><td class="line_label"><?=lang('notes')?></td><td><?=($i->notes) ? $i->notes : 'NULL'; ?></td></tr>
                              <?php if($i->attached_file) : ?>
                              <tr><td class="line_label"><?=lang('attachment')?></td><td><a href="<?=base_url()?>resource/uploads/<?=$i->attached_file?>" target="_blank">
                          <?=$i->attached_file?>
                          </a></td></tr>
                              <?php endif; ?>
                            </tbody>
                          </table>
                      </div>
                      <div class="col-md-6">                
                           <div class="bg-<?=config_item('theme_color')?> payment_received">
                                    <span> <?=lang('amount_received')?></span><br>
                                    <?php $cur = Invoice::view_by_id($i->invoice)->currency; ?>
                                    <span style="font-size:16pt;"><?=Applib::format_currency($cur, $i->amount)?></span>
                            </div>                       
                      </div>
                    </div> 
                      


                        <div class="mt_100">
                           <h4><?=lang('payment_for')?></h4>
                            <div style="clear:both;"></div>
                          </div>

                          <table class="payment_details" cellpadding="0" cellspacing="0" border="0">
                            <thead>
                              <tr class="h_40">
                                <td class="p_item">
                                  <?=lang('invoice_code')?>
                                </td>
                                <td class="p_item_r">
                                  <?=lang('invoice_date')?>
                                </td>
                                <td class="p_item_r">
                                  <?=lang('due_amount')?>
                                </td>
                                <td class="p_item_r">
                                  <?=lang('paid_amount')?>
                                </td>
                              </tr>
                            </thead>
                            <tbody>
                              <tr class="p_border">
                                <td class="pp_10" valign="top">
                                <a href="<?=base_url()?>invoices/view/<?=$i->invoice?>"><?=Invoice::view_by_id($i->invoice)->reference_no;?></a></td>
                                <td class="p_td" valign="top">
                <?=strftime(config_item('date_format'), strtotime(Invoice::view_by_id($i->invoice)->date_saved));?>
                                </td>
                                <td class="p_td" valign="top">
                                  <span>
                <?=Applib::format_currency($cur, Invoice::get_invoice_due_amount($i->invoice))?> </span>
                                </td>
                                <td class="p_td_r" valign="top">
                                  <span><?=Applib::format_currency($cur, $i->amount)?></span>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
               </div> 
              <!-- End Payment -->
         
        