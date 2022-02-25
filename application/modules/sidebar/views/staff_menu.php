<!-- .aside -->
   <!-- Left side column. contains the logo and sidebar -->
   <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        
      <?php  
                $user_id = User::get_id();
                $badge = array();
            

                $menus = $this->db->where('access',3)->where('visible',1)->where('parent','')->where('hook','main_menu_staff')->order_by('order','ASC')->get('hooks')->result();
                foreach ($menus as $menu) {
                    $sub = $this->db->where('access',3)->where('visible',1)->where('parent',$menu->module)->where('hook','main_menu_staff')->order_by('order','ASC')->get('hooks');
                    $perm = TRUE;
                    ?>

                  <?php if($menu->permission != '') { $perm = User::perm_allowed($user_id, $menu->permission); } ?>

                    <?php if($perm){ ?>
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
                                    <i class="fa <?=$submenu->icon?> icon"> </i>
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
                <?php } ?>

                   
      </ul>
    </section>
 
  </aside>