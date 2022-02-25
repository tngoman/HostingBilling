<?php if ($i->individual == 0) { ?>
    <!-- Client Contacts -->
 
            <table id="table-client-details-1" class="table table-striped b-t b-light text-sm AppendDataTables">
                <thead>
                <tr>
                    <th><?=lang('full_name')?></th>
                    <th><?=lang('email')?></th>
                    <th><?=lang('mobile_phone')?> </th>
                    <th>Skype</th>
                    <th class="col-date"><?=lang('last_login')?> </th>
                    <th class="col-options no-sort"><?=lang('options')?></th>
                </tr> </thead> <tbody>
                <?php foreach (Client::get_client_contacts($company) as $key => $contact) { ?>
                    <tr>
                        <td><a class="thumb-sm avatar">
                                <img src="<?php echo User::avatar_url($contact->user_id);?>" class="img-circle">
                            <?=$contact->fullname?>
                            </a>
                            </td>
                        <td class="text-info" ><?=$contact->email?> </td>
                        <td><a href="tel:<?=User::profile_info($contact->user_id)->phone?>"><b><i class="fa fa-phone"></i></b> <?=User::profile_info($contact->user_id)->phone?></a></td>
                        <td><a href="skype:<?=User::profile_info($contact->user_id)->skype?>?call"><?=User::profile_info($contact->user_id)->skype?></a></td>
                        <?php
                        if ($contact->last_login == '0000-00-00 00:00:00') {
                            $login_time = "-";
                        }else{ $login_time = strftime(config_item('date_format')." %H:%M:%S", strtotime($contact->last_login)); } ?>
                        <td><?=$login_time?> </td>
                        <td>

                            <a href="<?=base_url()?>companies/send_invoice/<?=$contact->user_id?>/<?=$i->co_id?>" class="btn btn-default btn-xs" title="<?=lang('email_invoice')?>" data-toggle="ajaxModal">
                                <i class="fa fa-envelope"></i> </a>

                            <a href="<?=base_url()?>companies/make_primary/<?=$contact->user_id?>/<?=$i->co_id?>" class="btn btn-default btn-xs" title="<?=lang('primary_contact')?>" >
                                <i class="fa fa-chain <?php if ($i->primary_contact == $contact->user_id) { echo "text-danger"; } ?>"></i> </a>
                            <a href="<?=base_url()?>contacts/update/<?=$contact->user_id?>" class="btn btn-default btn-xs" title="<?=lang('edit')?>"  data-toggle="ajaxModal">
                                <i class="fa fa-edit"></i> </a>
                            <a href="<?=base_url()?>users/account/delete/<?=$contact->user_id?>" class="btn btn-default btn-xs" title="<?=lang('delete')?>" data-toggle="ajaxModal">
                                <i class="fa fa-trash-o"></i> </a>
                        </td>
                    </tr>
                <?php  } ?>



                </tbody>
            </table> 
<?php } ?>
