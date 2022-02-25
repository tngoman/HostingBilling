  
<small><?=lang('welcome_back')?> , <?php echo User::displayName(User::get_id());?> </small>
 <?php
            $user = User::get_id();            
            $user_company = User::profile_info($user)->company;
            $cur = Client::client_currency($user_company);
             $client_paid = 0;
 
            if ($user_company > 0) {
                $client_paid = Client::client_amount_paid($user_company);

                $client_outstanding = Client::client_due_amount($user_company);

                $client_payments = Client::client_amount_paid($user_company);

                $client_payable = Client::client_payable($user_company);

                if ($client_payable > 0 && $client_payments > 0) {
                    $perc_paid = round(($client_payments/$client_payable) * 100,1);
                    $perc_paid = ($perc_paid > 100) ? '100' : $perc_paid;
                }else{
                    $perc_paid = 0;
                }

 
                ?>

            <?php } else {

                $client_outstanding = $perc_paid = $client_payable = 0;
                $perc_open = 0;
            }
            ?>


<div class="box-body">

<div class="row status_blocks">
        <div class="col-sm-6 col-md-3">
            <div class="info-box bg-teal-gradient">
            <a class="clear" href="<?= base_url() ?>domains">
                    <span class="info-box-icon"><i class="fa fa-globe fa-1x text-white"></i></span>
                    <span class="status_count"><?=Order::client_domains($user_company);?></span><br>									 
                    <span class="label"><?= lang('domains') ?> </span>										 					
                </a>
            </div>
        </div>



        <div class="col-sm-6 col-md-3">
            <div class="info-box bg-green-gradient">
                <a class="clear" href="<?= base_url() ?>accounts">
                    <span class="info-box-icon"><i class="fa fa-server fa-1x text-white"></i></span>	
                    <span class="status_count"><?=Order::client_accounts($user_company);?></span><br>									 
                    <span class="label"><?= lang('accounts') ?> </span>
                </a>					
            </div>
        </div>
            

        
        <div class="col-sm-6 col-md-3">
            <div class="info-box bg-light-blue">
                <a class="clear" href="<?= base_url() ?>invoices?view=unpaid">
                <span class="info-box-icon"><i class="fa fa-shopping-basket fa-1x"></i></span>										 
                <span class="status_count"><?=App::counter('invoices',array('client'=>$user_company,'status !='=>'Cancelled','status !='=>'Deleted','status'=>'Unpaid'));?></span><br>									 
                <span class="label"><?= lang('unpaid_invoices') ?> </span>							
                </a>						
            </div>
        </div>



        <div class="col-sm-6 col-md-3">
            <div class="info-box bg-purple-gradient">
                <a class="clear" href="<?= base_url() ?>tickets">
                <span class="info-box-icon"><i class="fa fa-support fa-1x text-white"></i></span>
                <span class="status_count"><?=App::counter('tickets',array('reporter'=>$user,'status !='=>'closed'));?></span><br>									 
                <span class="label"><?= lang('active_tickets') ?> </span>								
                </a>
            </div>
        </div> 
    </div>


  
 
            <div class="row">
               
                <div class="col-lg-4"> 
                    <section class="box box-default">
                        <header class="box-header"><?=lang('payments')?> </header>
                        <div class="box-body text-center"> <h4><small> <?=lang('paid_amount')?> : </small>
                                <?php echo Applib::format_currency($cur->code, Applib::client_currency($cur->code, Client::amount_paid($user_company))); ?></h4>
                            <small class="text-muted block">
                                <?=lang('outstanding')?> : <?=Applib::format_currency($cur->code, $client_outstanding)?>
                            </small>
                            <div class="inline">

                                <div class="easypiechart" data-percent="<?=$perc_paid?>" data-line-width="16" data-loop="false" data-size="188">

                                    <span class="h2 step"><?=$perc_paid?></span>%
                                    <div class="easypie-text"><?=lang('paid')?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer center"><small><?=lang('total')?>:
                                <strong><?=Applib::format_currency($cur->code, $client_payable)?></strong></small>
                        </div> 
                    </section>
                </div>

                   <!-- Start Tickets -->
                   <div class="col-lg-8">
                        <section class="box box-warning box-solid">
                            <header class="box-header with-border">
                                <?=lang('active_accounts')?>
                            </header>
                            <div class="box-body">

                            <section class="slim-scroll" data-height="285" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">

                                <div class="list-group bg-white">
                                    <?php
                                    $orders = Order::client_orders($user_company);  
                                    foreach ($orders as $key => $order) { 
                                        $earlier = new DateTime(Date('Y-m-d'));
                                        $later = new DateTime($order->renewal_date);
                                        $remaining = $later->diff($earlier)->format("%a"); ?>
                                        <span href="#" class="list-group-item">
                                       <span class="label label-default">  <?=($order->type == 'domain' || $order->type == 'domain_only') ? $order->domain : $order->item_name; ?> </span> <span class="pull-right"><small><?=lang('next_payment')?>:</small> <span class="label <?=($remaining < 10) ? 'label-warning' : 'label-default'?>"> <?=$remaining?></span><small><?=strtolower(lang('days'))?></small></span> 
                                    </span>
                                    <?php  } ?>
                                </div>

                                </section>

                            </div>

                        </section>
                       </div>
                        <!-- End Tickets -->


                   
                
            </div>
            <div class="row">
                
                <!-- Recent activities -->
                <div class="col-md-8">
                    <section class="box box-default">
                        <div class="box-header"><?= lang('recent_activities') ?></div>
                        <div class="box-body">
                            <section class="comment-list block">
                                <section class="slim-scroll" data-height="400" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
                                    <?php foreach (Client::recent_activities($user) as $key => $activity) { ?>
                                        <article id="comment-id-1" class="comment-item small">
                                            <div class="pull-left thumb-sm">

                                                <img src="<?php echo User::avatar_url($activity->user); ?>" class="img-circle">

                                            </div>
                                            <section class="comment-body m-b-lg">
                                                <header class="b-b">
                                                    <strong>
                                                        <?php echo User::displayName($activity->user); ?></strong>
									<span class="text-muted text-xs">
							<?php echo Applib::time_elapsed_string(strtotime($activity->activity_date));?>
									</span>
                                                </header>
                                                <div>
                                                    <?php
                                                    if (lang($activity->activity) != '') {
                                                        if (!empty($activity->value1)) {
                                                            if (!empty($activity->value2)) {
                                                                echo sprintf(lang($activity->activity), '<em>' . $activity->value1 . '</em>', '<em>' . $activity->value2 . '</em>');
                                                            } else {
                                                                echo sprintf(lang($activity->activity), '<em>' . $activity->value1 . '</em>');
                                                            }
                                                        } else {
                                                            echo lang($activity->activity);
                                                        }
                                                    } else {
                                                        echo $activity->activity;
                                                    }
                                                    ?>
                                                </div>
                                            </section>
                                        </article>
                                    <?php } ?>
                                </section>
                            </section>
                        </div>
                    </section>
                </div>


                 <!-- Start Tickets -->
                 <div class="col-lg-4">
                        <section class="box box-info">
                            <header class="box-header">
                                <?=lang('recent_tickets')?>
                            </header>
                            <div class="box-body">
                            <section class="slim-scroll" data-height="390" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
                                <div class="list-group bg-white">
                                    <?php
                                    $tickets = Ticket::by_where(array('reporter'=>$user)); // Get 7 tickets
                                    foreach ($tickets as $key => $ticket) {
                                        if($ticket->status == 'open'){ $badge = 'danger'; }elseif($ticket->status == 'closed'){ $badge = 'success'; }else{ $badge = 'dark'; }
                                        ?>
                                        <a href="<?=base_url()?>tickets/view/<?=$ticket->id?>" data-original-title="<?=$ticket->subject?>" data-toggle="tooltip" data-placement="top" title = "" class="list-group-item">
                                            <?=$ticket->ticket_code?> - <small class="text-muted"><?=lang('priority')?>: <?=$ticket->priority?> <span class="badge bg-<?=$badge?> pull-right"><?=$ticket->status?></span></small>
                                        </a>
                                    <?php  } ?>
                                </div>
                              </section>

                            </div>

                        </section>
                       </div>
                        <!-- End Tickets -->


            </div>
        </section>
    </section>
    <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
    </section>
