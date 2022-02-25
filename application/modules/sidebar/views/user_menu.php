<!-- .aside -->
   <!-- Left side column. contains the logo and sidebar -->
   <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
              <?php
              $user_id = User::get_id();
              $client_co = User::profile_info($user_id)->company;
              $client = Client::view_by_id($client_co);      
              $cur = Client::client_currency($client_co);         
              $badge = array();
                
                $menus = $this->db->where('access',2)->where('visible',1)->where('parent','')->where('hook','main_menu_client')->order_by('order','ASC')->get('hooks')->result();
                foreach ($menus as $menu) {
                    $sub = $this->db->where('access',2)->where('visible',1)->where('parent',$menu->module)->where('hook','main_menu_client')->order_by('order','ASC')->get('hooks');

                    ?>
                    <?php if ($sub->num_rows() > 0) {
                        $submenus = $sub->result(); ?>
                        <li class="<?php
                            foreach ($submenus as $submenu) {
                                if($page == lang($submenu->name)){echo  "active"; }
                            }
                        ?>">
                            <a href="<?=base_url()?><?=$menu->route?>">
                                <i class="fa <?=$menu->icon?> icon"> </i>
                                <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i></span>
                                <span><?=lang($menu->name)?></span> </a>
                            <ul class="nav lt">
                            <?php foreach ($submenus as $submenu) { ?>
                            <li class="<?php if($page == lang($submenu->name)){echo  "active"; }?>">
                                <a href="<?=base_url()?><?=$submenu->route?>">
                                    <?php if (isset($badge[$submenu->module])) { echo $badge[$menu->module]; } ?>
                                    <i class="fa <?=$submenu->icon?> icon">  </i>
                            <span><?=lang($submenu->name)?></span> </a> </li>
                            <?php } ?>
                            </ul>
                        </li>
                    <?php } else { ?>
                        <li class="<?php if($page == lang($menu->name)){echo  "active"; }?>">
                            <a href="<?=base_url()?><?=$menu->route?>">
                            <?php if (isset($badge[$menu->module])) { echo $badge[$menu->module]; } ?>
                            <i class="fa <?=$menu->icon?> icon"> 
                        </i>
                        <span><?=lang($menu->name)?></span> </a> </li>
                    <?php } ?> 
                <?php } ?>
                
                <li><a href="<?=base_url()?>invoices/add_funds/<?=$client_co?>" data-toggle="ajaxModal"> 
                    <i class="fa fa-bank icon"></i>
                <span><?=lang('credit_balance')?></span> <span class="pull-right"><?php echo Applib::format_currency($cur->code, Applib::client_currency($cur->code, $client->transaction_value)); ?></span> </a> </li>

                <?php if($this->session->userdata('admin')) { ?>
                <li class="header">ADMIN</li>
                <li><a class="btn btn-sm btn-block" href="<?=base_url()?>profile/switch_back">Switch Back</a></li>
                <?php } ?>
       
                </ul>

                
    </section>
 
  </aside>
<!-- /.aside -->
