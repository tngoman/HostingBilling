


                            <div class="row proj-summary-band">

                                <div class="col-md-3 text-center">
                                    <label class="text-muted"><?=lang('this_month')?></label>
                                    <h4 class="cursor-pointer text-open small"><?=lang('payments')?></h4>
                                    <h4><strong>
                            <?=Applib::format_currency($cur->code, Client::month_amount(date('Y'),date('m'),$i->co_id));?>
                        </strong>
                    </h4>
                                </div>

                                <div class="col-md-3 text-center">
                                <label class="text-muted"><?=lang('balance_due')?></label>
                                    <h4 class="cursor-pointer text-open small">- <?=lang('total')?></h4>
                                    <h4><strong><?=Applib::format_currency($cur->code, $due);?></strong></h4>
                                </div>
 

                                <div class="col-md-3 text-center">
                                <label class="text-muted"><?=lang('received_amount')?></label>
                                    <h4 class="cursor-pointer text-success small"><?=lang('total_receipts')?></h4>
                                    <h4><strong><?=Applib::format_currency($cur->code, Client::amount_paid($i->co_id))?></strong></h4>
                                </div>

                            </div>



                            <div class="row mt_10">
                                <div class="col-lg-6">

                                    <section class="panel panel-default">
                                        <header class="panel-heading"><?=$i->company_name?> - <?=lang('details')?></header>



                                        <ul class="list-group no-radius">
                                            <li class="list-group-item">
                                                <span class="pull-right text"><?=$i->company_name?></span>
                                                <span class="text-muted">
                                                    <?php echo ($i->individual == 0) ? lang('company_name') : lang('full_name');  ?>
                                                </span>
                                            </li>

                                            <?php if ($i->individual == 0) { ?>
                                                <li class="list-group-item">
                                                    <span class="pull-right">
                                                    <?=($i->primary_contact) ? User::displayName($i->primary_contact) : ''; ?>
                                                    </span>
                                                    <span class="text-muted">
                                                        <?=lang('contact_person')?>
                                                    </span>
                                            </li>
                                            <?php } ?>

                                            <li class="list-group-item">
                                                <span class="pull-right">
                                        <a href="mailto:<?=$i->company_email?>"><?=$i->company_email?></a>
                                                </span>
                                                <span class="text-muted"><?=lang('email')?></span>

                                            </li>


                                            <li class="list-group-item">
                                                <span class="pull-right">
                                        <a href="tel:<?=$i->company_phone?>"><?=$i->company_phone?></a>
                                                </span>
                                                <span class="text-muted"><?=lang('phone')?></span>

                                            </li>
                                            <li class="list-group-item">
                                                <span class="pull-right">
                                        <a href="tel:<?=$i->company_mobile?>"><?=$i->company_mobile?></a>
                                                </span>
                                                <span class="text-muted"><?=lang('mobile_phone')?></span>

                                            </li>

                                            <li class="list-group-item">
                                                <span class="pull-right">
                                        <a href="tel:<?=$i->company_fax?>"><?=$i->company_fax?></a>
                                                </span>
                                                <span class="text-muted"><?=lang('fax')?></span>

                                            </li>

                                            <li class="list-group-item">
                                                <span class="pull-right">
                                        <?=$i->VAT?>
                                                </span>
                                                <span class="text-muted"><?=lang('tax')?> <sup>No</sup></span>

                                            </li>

                                            <li class="list-group-item">
                                                <span class="pull-right">
                                        <?=nl2br($i->company_address)?>
                                                </span>
                                                <span class="text-muted"><?=lang('address')?></span>

                                            </li>

                                            <li class="list-group-item">
                                                <span class="pull-right">
                                        <?=$i->city?>
                                                </span>
                                                <span class="text-muted"><?=lang('city')?></span>

                                            </li>

                                            <li class="list-group-item">
                                                <span class="pull-right">
                                        <?=$i->zip?>
                                                </span>
                                                <span class="text-muted"><?=lang('zip_code')?></span>

                                            </li>

                                            <li class="list-group-item">
                                                <span class="pull-right">
                                        <?=$i->state?>
                                                </span>
                                                <span class="text-muted"><?=lang('state_province')?></span>

                                            </li>

                                            <li class="list-group-item">
                                                <span class="pull-right">
                                        <?=$i->country?>
                                                </span>
                                                <span class="text-muted"><?=lang('country')?></span>

                                            </li>


                                        </ul>

                                    </section>

                                </div>
                                <!-- End details C1-->


                                <!-- start extra fields-->

                                <div class="col-sm-6">
                                    <section class="panel panel-default">
                                        <header class="panel-heading"><?=lang('additional_fields')?></header>


                                        <ul class="list-group no-radius">                                           

                                        <?php $custom_fields = Client::custom_fields($i->co_id); ?>
                                        <?php foreach ($custom_fields as $key => $f) : ?>
                                            <?php if($this->db->where('name',$f->meta_key)->get('fields')->num_rows() > 0): ?>
                                            <li class="list-group-item">
                                                    <span class="pull-right">
                                                        <?=is_json($f->meta_value) ? implode( ',',json_decode($f->meta_value)) : $f->meta_value ;?></span>
                                                    <span class="text-muted"><?=ucfirst(humanize($f->meta_key,'-'))?></span>

                                            </li>
                                        <?php endif; ?>
                                        <?php endforeach; ?>



                                        </ul>

                                    </section>
                                </div>

                                <!-- end extra fields -->



                            </div>
                            <div class="line line-dashed line-lg pull-in"></div>

                            <div class="small text-muted panel-body m-sm">
                                <p><?=($i->notes == '') ? 'No Notes' : nl2br_except_pre($i->notes);?></p>
                            </div>
