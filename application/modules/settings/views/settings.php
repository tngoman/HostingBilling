<div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile"> 
              <h3 class="profile-username text-center"><?=lang('settings_menu')?></h3> 

              <ul class="list-group" id="settings_menu">
              <?php      
                           
                            $menus = $this->db->where('hook','settings_menu_admin')->where('visible',1)->order_by('order','ASC')->get('hooks')->result();
                            foreach ($menus as $menu) { ?>
                                <li class="list-group-item <?php echo ($load_setting == $menu->route) ? 'active' : '';?>">
                                    <a href="<?=base_url()?>settings/?settings=<?=$menu->route?>">
                                        <i class="fa fa-fw <?=$menu->icon?>"></i>
                                        <?=lang($menu->name)?>
                                    </a>
                                </li>
                            <?php } ?>
              </ul>
            </div>
              <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="box box-warning">
                <div class="box-header clearfix">
                    <div class="row m-t-sm">
                        <div class="col-sm-10 m-b-xs">
                        <?php if(config_item('demo_mode') != 'TRUE') {?>
                            <?php if($load_setting == 'templates'){  ?>
                                <div class="btn-group">
                                    <a class="btn btn-twitter" href="<?=base_url()?>settings/?settings=templates&group=user"><?=lang('account_emails')?></a>
                                     <a class="btn btn-vk" href="<?=base_url()?>settings/?settings=templates&group=invoice"><?=lang('invoicing_emails')?></a>
                                     <a class="btn btn-warning" href="<?=base_url()?>settings/?settings=templates&group=ticket"><?=lang('ticketing_emails')?></a>
                                      <a class="btn btn-primary" href="<?=base_url()?>settings/?settings=templates&group=signature"><?=lang('email_signature')?></a>                                     
                                </div>
                            <?php } ?>
                       
                            <?php $set = array('system', 'validate');
                            if( in_array($load_setting, $set) && config_item('demo_mode') != 'TRUE'){  ?>
                           
                            <a href="<?=base_url()?>settings/database" class="btn btn-<?=config_item('theme_color');?> btn-sm"><i class="fa fa-cloud-download text"></i>
                                    <span class="text"><?=lang('database_backup')?></span>
                                </a>                                
                            <?php } ?>

                            <?php if($load_setting == 'email'){  ?>
                                <a href="<?=base_url()?>settings/?settings=email&view=alerts" class="btn btn-sm btn-<?=config_item('theme_color');?>"><i class="fa fa-inbox text"></i>
                                    <span class="text"><?=lang('alert_settings')?></span>
                                </a>
                            <?php } ?>

                            <?php
                            } ?>

                        </div>
                    </div>
                 </div>
                <div class="box-body" id="settings">
                <?=$this->load->view($load_setting)?>
              </div>
            </div>          
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

 