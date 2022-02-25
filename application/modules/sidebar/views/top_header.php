<?php $up = count($updates); 
        $user = User::get_id();
        $user_email = User::login_info($user)->email;
 ?>
 
<header class="main-header">

<a target="_balnk" href="<?=base_url()?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><img src="<?=base_url()?>resource/images/<?=config_item('company_logo')?>"></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><?php $display = config_item('logo_or_icon'); ?>

		<?php if ($display == 'logo' || $display == 'logo_title') { ?>
			<img src="<?=base_url()?>resource/images/<?=config_item('company_logo')?>">
		<?php } elseif ($display == 'icon' || $display == 'icon_title') { ?>
			<i class="fa <?=config_item('site_icon')?>"></i>
		<?php } ?>

		<?php 
		if ($display == 'logo_title' || $display == 'icon_title') {
			if (config_item('website_name') == '') { echo config_item('company_name'); } else { echo config_item('website_name'); }
		} ?></span>
    </a>

    
    <nav class="navbar navbar-static-top">  
      
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a> 
 
    
        <div class="navbar-header">
    
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <ul class="nav navbar-nav">              
        
        <?php 
       if($this->session->userdata('role_id') == 1 || $this->session->userdata('role_id') == 3) { 
          $menus = $this->db->where('access',1)->where('visible',1)->where('parent','')->where('hook','main_menu_admin')->order_by('order','ASC')->get('hooks')->result();
          foreach ($menus as $menu) {
              $sub = $this->db->where('access',1)->where('visible',1)->where('parent',$menu->module)->where('hook','main_menu_admin')->order_by('order','ASC')->get('hooks'); ?>
              <?php if ($sub->num_rows() > 0) {
                  $submenus = $sub->result(); ?>
                  <li class="dropdown <?php if(lang($menu->name) == lang('website')) {echo 'website active';}
                      foreach ($submenus as $submenu) {
                          if($page == lang($submenu->name)){echo  "active"; }
                      }
                  ?>" >
                      <a class="dropdown-toggle" data-toggle="dropdown"> <?=lang($menu->name)?> <span class="caret"></span></a>
                      <ul class="dropdown-menu" role="menu">
                      <?php foreach ($submenus as $sub) { ?>
                      <li>
                          <a href="<?=base_url()?><?=$sub->route?>"> 
                              <?=lang($sub->name)?></a> </li>
                      <?php } ?>
                      </ul>
                  </li>
              <?php }
              
              else { ?>
                  <li class="<?php if($page == lang($menu->name)){echo  "active"; }?>">
                      <a href="<?=base_url()?><?=$menu->route?>">
              <span><?=lang($menu->name)?></span> 
              <?php if(lang($menu->name) == lang('support')) { ?> &nbsp; <span class="pull-right-container"> <span class="label label-warning pull-right"> <?php echo App::counter('tickets',array('status =' => 'open'));?></span></span> <?php } ?> 
          </a> </li>
   
              <?php } ?>
          <?php }} ?>
  
            <li><a href="<?=base_url()?>cart/shopping_cart"><i class="fa fa-shopping-basket"></i></a></li>
         
        </ul>
         
        </div>
        <!-- /.navbar-collapse -->
 
        
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          
        <ul class="nav navbar-nav">
        
  
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu pull-right">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo User::avatar_url($user);?>" class="user-image" alt="User">
              <span class="hidden-xs"><?php echo User::displayName($user);?></span>
            </a>
            <ul class="dropdown-menu">
             
              <!-- Menu Body -->
          
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="btn-group pull-left">
                  <a href="<?=base_url()?>profile/settings" class="btn btn-twitter btn-sm btn-flat"><?=lang('profile')?></a>                
                  <a href="<?=base_url()?>profile/activities" class="btn btn-warning btn-sm btn-flat"><?=lang('activities')?></a>
                </div>
                <div class="pull-right">
                  <a href="<?=base_url()?>logout" class="btn btn-danger btn-sm btn-flat"><?=lang('logout')?></a>
                </div>
              </li>
            </ul>
          </li>
         
        </ul>
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>

 