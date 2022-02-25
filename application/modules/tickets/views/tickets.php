<!-- Start -->
          <div class="box">
            <div class="box-header">
              <div class="btn-group">

              <button class="btn btn-<?=config_item('theme_color');?> btn-sm">
              <?php
              $view = isset($_GET['view']) ? $_GET['view'] : NULL;
              switch ($view) {
                case 'pending':
                  echo lang('pending');
                  break;
                case 'closed':
                  echo lang('closed');
                  break;
                case 'open':
                  echo lang('open');
                  break;
                case 'resolved':
                  echo lang('resolved');
                  break;

                default:
                  echo lang('filter');
                  break;
              }
              ?></button>
              <button class="btn btn-<?=config_item('theme_color');?> btn-sm dropdown-toggle" data-toggle="dropdown"><span class="caret"></span>
              </button>
              <ul class="dropdown-menu">

              <li><a href="<?=base_url()?>tickets?view=pending"><?=lang('pending')?></a></li>
              <li><a href="<?=base_url()?>tickets?view=closed"><?=lang('closed')?></a></li>
              <li><a href="<?=base_url()?>tickets?view=open"><?=lang('open')?></a></li>
              <li><a href="<?=base_url()?>tickets?view=resolved"><?=lang('resolved')?></a></li>
              <li><a href="<?=base_url()?>tickets"><?=lang('all_tickets')?></a></li>

              </ul>
              </div> 

              <div class="btn-group pull-right">
              <a href="<?=base_url()?>tickets/add" class="btn btn-sm btn-warning"><?=lang('create_ticket')?></a>

              <?php if(!User::is_client()) { ?>
                  <?php if ($archive) : ?>
                <a href="<?=base_url()?>tickets" class="btn btn-sm btn-primary"><?=lang('view_active')?></a>
                <?php else: ?>
              <a href="<?=base_url()?>tickets?view=archive" class="btn btn-sm btn-primary"><?=lang('view_archive')?></a> 
              <?php endif; ?>
              <?php } ?>

                </div>
              </div>

              <div class="box-body">
              <div class="table-responsive">
                <table id="table-tickets<?=($archive) ? '-archive':''?>" class="table table-striped b-t b-light AppendDataTables">
                  <thead>
                    <tr>
                    <th class="w_5 hidden"></th>
                   <th><?=lang('subject')?></th>
                   <?php if (User::is_admin() || User::is_staff()) { ?>
                   <th><?=lang('reporter')?></th>
                    <?php } ?>
                    <th class="col-date"><?=lang('date')?></th>
                    <th class="col-options no-sort"><?=lang('priority')?></th>

                      <th class="col-lg-1"><?=lang('department')?></th>
                      <th class="col-lg-1"><?=lang('status')?></th>
                      <th><?=lang('options')?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                        $this->load->helper('text');
                        foreach ($tickets as $key => $t) {
                        $s_label = 'default';
                        if($t->status == 'open') $s_label = 'danger';
                        if($t->status == 'closed') $s_label = 'success';
                        if($t->status == 'resolved') $s_label = 'primary';
                    ?>
                    <tr>
                    <td class="hidden"><?=$t->id?></td>


              <td style="border-left: 2px solid <?php echo ($t->status == 'closed') ? '#1ab394' : '#F8AC59'; ?>;">

              <?php $rep = $this->db->where('ticketid',$t->id)->get('ticketreplies')->num_rows();
                    if($rep == 0){ ?>

                <a class="text-info <?=($t->status == 'closed') ? 'text-lt' : ''; ?>" href="<?=base_url()?>tickets/view/<?=$t->id?>" data-toggle="tooltip" data-title="<?=lang('ticket_not_replied')?>">
                     <?php }else{ ?>
                <a class="text-info <?=($t->status == 'closed') ? 'text-lt' : ''; ?>" href="<?=base_url()?>tickets/view/<?=$t->id?>">
                      <?php } ?>

                     <?=word_limiter($t->subject, 8);?>
                     </a><br>
                     <?php if($rep == 0 && $t->status != 'closed'){ ?>
                     <span class="text-danger">Pending for <?=Applib::time_elapsed_string(strtotime($t->created));?></span>
                     <?php } ?>

                      </td>
                      <?php if (User::is_admin() || User::is_staff()) { ?>

                      <td>
                      <?php
                      if($t->reporter != NULL){ ?>
                        <a class="pull-left thumb-sm avatar" data-toggle="tooltip" title="<?php echo User::login_info($t->reporter)->email; ?>" data-placement="right">
                                <img src="<?php echo User::avatar_url($t->reporter); ?>" class="img-rounded radius_6">
                                <?php echo User::displayName($t->reporter); ?>
                          &nbsp;

                            </a>
                      <?php } else { echo "NULL"; } ?>

                      </td>

                      <?php } ?>



                       <td class=""><?=date("D, d M g:i:A",strtotime($t->created));?><br/>
                      <span class="text-primary">(<?=Applib::time_elapsed_string(strtotime($t->created));?>)</span>
                       </td>

                      <td>
                      <span class="label label-<?php if($t->priority == 'Urgent') { echo 'danger'; }elseif($t->priority == 'High') { echo 'warning'; }else{ echo 'default'; } ?>"> <?=$t->priority?></span>
                      </td>







                      <td class="">
                      <?php echo App::get_dept_by_id($t->department); ?>
                      </td>

                      <td>
                       <?php
                                    switch ($t->status) {
                                        case 'open':
                                            $status_lang = 'open';
                                            break;
                                        case 'closed':
                                            $status_lang = 'closed';
                                            break;
                                        case 'pending':
                                            $status_lang = 'pending';
                                            break;
                                        case 'resolved':
                                            $status_lang = 'resolved';
                                            break;

                                        default:
                                        $status_lang = 'active';
                                            break;
                                    }
                                    ?>
                                    <span class="label label-<?=$s_label?>"><?=ucfirst(lang($status_lang))?></span> </td>

                                    <td>
                                    <a data-toggle="tooltip" data-original-title="<?=lang('view')?>" data-placement="top" class="btn btn-success btn-xs" href="<?=base_url()?>tickets/view/<?=$t->id?>"><i class="fa fa-eye"></i></a>

                                    <?php if (User::is_admin()) { ?>

                                    <a data-toggle="tooltip" data-original-title="<?=lang('edit')?>" data-placement="top" class="btn btn-twitter btn-xs" href="<?=base_url()?>tickets/edit/<?=$t->id?>"><i class="fa fa-pencil"></i></a>
                                    <a class="btn btn-google btn-xs" href="<?=base_url()?>tickets/delete/<?=$t->id?>" data-toggle="ajaxModal" title="<?=lang('delete_ticket')?>"><i class="fa fa-trash"></i></a></li>
                                        <?php if ($archive) : ?>
                                        <a data-toggle="tooltip" data-original-title="<?=lang('move_to_active')?>" data-placement="top" class="btn btn-primary btn-xs" href="<?=base_url()?>tickets/archive/<?=$t->id?>/0"><i class="fa fa-sign-in"></i></a>
                                        <?php else: ?>
                                        <a data-toggle="tooltip" data-original-title="<?=lang('archive_ticket')?>" data-placement="top" class="btn btn-primary btn-xs" href="<?=base_url()?>tickets/archive/<?=$t->id?>/1"><i class="fa fa-archive"></i></a>
                                        <?php endif; ?>
                                    <?php } ?>
                                     </td>

                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
       </div>
</div>
          