<!-- Client Payments -->
<?php if(User::is_admin() || User::perm_allowed(User::get_id(),'view_all_payments')) { ?>
<table id="table-payments" class="table table-striped b-t b-light AppendDataTables">
    <thead>
        <tr>
            <th class="w_5 hidden"></th>
            <th><?=lang('date')?></th>
            <th><?=lang('invoice')?></th>
            <th class=""><?=lang('payment_method')?></th>
            <th><?=lang('amount')?> </th>
            <th><?=lang('options')?> </th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach (Payment::client_payments($company) as $key => $p) {
            $cur = Client::client_currency($p->paid_by); ?>
        <tr>
            <td class="hidden"><?=$p->p_id?></td>
            <td>
                <a class="text-info" href="<?=base_url()?>payments/view/<?=$p->p_id?>">
                    <?=strftime(config_item('date_format'), strtotime($p->created_date));?>
                </a>
            </td>
            <td><a class="text-info" href="<?=base_url()?>invoices/view/<?=$p->invoice?>">
                    <?php echo Invoice::view_by_id($p->invoice)->reference_no;?>
                </a>
            </td>
            <td>
                <label class="label label-default">
                    <?php echo App::get_method_by_id($p->payment_method); ?>
                </label>
            </td>
            <td>
                <strong><?php echo Applib::format_currency($cur->code, Applib::client_currency($cur->code, $p->amount)); ?></strong>
            </td>

            <td>
                <a class="btn btn-xs btn-primary" href="<?=base_url()?>payments/view/<?=$p->p_id?>"
                    data-toggle="tooltip" data-original-title="<?=lang('view_payment')?>" data-placement="top"><i
                        class="fa fa-eye"></i></a>

                <a class="btn btn-xs btn-warning" href="<?=base_url()?>payments/pdf/<?=$p->p_id?>" data-toggle="tooltip"
                    data-original-title="<?=lang('pdf')?> <?=lang('receipt')?>" data-placement="top"><i
                        class="fa fa-file-pdf-o"></i></a>


                <?php if(User::is_admin() || User::perm_allowed(User::get_id(),'edit_payments')){ ?>
                <a class="btn btn-xs btn-success" data-toggle="tooltip" data-original-title="<?=lang('edit_payment')?>"
                    data-placement="top" href="<?=base_url()?>payments/edit/<?=$p->p_id?>"><i
                        class="fa fa-pencil"></i></a>
                <?php if($p->refunded == 'No'){ ?>
                <span data-toggle="tooltip" data-original-title="<?=lang('refund')?>" data-placement="top"><a
                        class="btn btn-xs btn-twitter" href="<?=base_url()?>payments/refund/<?=$p->p_id?>"
                        data-toggle="ajaxModal"><i class="fa fa-warning"></i></a></span>

                <?php } } ?>
                <a class="btn btn-xs btn-google" href="<?=base_url()?>payments/delete/<?=$p->p_id?>"
                    data-toggle="ajaxModal"><i class="fa fa-trash"></i></a>

            </td>

        </tr>
        <?php } ?>



    </tbody>
</table>
<?php } ?>