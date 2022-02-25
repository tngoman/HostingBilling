<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">
            <?php $display = config_item('logo_or_icon'); ?>
            <?php if ($display == 'logo' || $display == 'logo_title') { ?>
            <img src="<?=base_url()?>resource/images/<?=config_item('company_logo')?>" class="img-responsive <?=($display == 'logo' ? "" : "thumb-sm m-r-sm")?>">
            <?php } elseif ($display == 'icon' || $display == 'icon_title') { ?>
            <i class="fa <?=config_item('site_icon')?>"></i>
            <?php } ?>
            <?php
                    if ($display == 'logo_title' || $display == 'icon_title') {
                        if (config_item('website_name') == '') {
                          echo config_item('company_name');
                        } else {
                          echo config_item('website_name'); }
                      }
                ?>
          </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">        
          <div class="navbar-right">
          <ul class="nav navbar-nav">
            <li <?php echo ($this->uri->segment(1) == '') ? 'class="active"' : '' ?>><a href="<?=base_url()?>"><i class="fa fa-fw fa-home"></i> <?=lang('home')?></a></li>
            <li <?php echo ($this->uri->segment(1) == '') ? 'class="active"' : '' ?>><a href="<?=base_url()?>contact" ><i class="fa fa-fw fa-envelope"></i> <?=lang('contact')?></a></li>
            
            <?php if(!$this->session->userdata('user_id')) { ?>
            <li <?php echo ($this->uri->segment(1) == '') ? 'class="active"' : '' ?>><a href="<?=base_url()?>login"><i class="fa fa-fw fa-user"></i> <?=lang('login')?></a></li>
            <?php } else { ?>

              <li><a href="<?=base_url()?>dashboard"><i class="fa fa-fw fa-dashboard"></i> <?=lang('dashboard')?></a></li>

            <?php } ?>

            <li <?php echo ($this->uri->segment(1) == '') ? 'class="active"' : '' ?>> <?php if(config_item('cart') == 'js') { ?>
           <a href="#" id="shopping_cart"><i class="fa fa-fw fa-shopping-cart"></i> <?=lang('cart')?> <span class="badge badge-warning" id="cart_count">0</span></a>
        <?php } else { ?>
            <a href="<?=base_url()?>home/shopping_cart"><i class="fa fa-fw fa-shopping-cart"></i> <?=lang('cart')?> <span class="badge badge-warning"><?= count($this->session->userdata('cart'))?></span></a>
          <?php } ?></li>
          <?php if(config_item('enable_languages') == 'TRUE'){ ?>
            <li>
            <div class="btn-group dropdown">                                
                                
						<button type="button" class="btn btn-sm dropdown-toggle btn-<?=config_item('theme_color');?>" data-toggle="dropdown" btn-icon="" title="<?=lang('languages')?>"><i class="fa fa-globe"></i></button>
						<button type="button" class="btn btn-sm btn-primary dropdown-toggle  hidden-nav-xs" data-toggle="dropdown"><?=lang('languages')?> <span class="caret"></span></button>
              <!-- Load Languages -->
                    <ul class="dropdown-menu text-left">
                    <?php $languages = App::languages(); foreach ($languages as $lang) : if ($lang->active == 1) : ?>
                    <li>
                        <a href="<?=base_url()?>set_language?lang=<?=$lang->name?>" title="<?=ucwords(str_replace("_"," ", $lang->name))?>">
                            <img src="<?=base_url()?>resource/images/flags/<?=$lang->icon?>.gif" alt="<?=ucwords(str_replace("_"," ", $lang->name))?>"  /> <?=ucwords(str_replace("_"," ", $lang->name))?>
                        </a>
                    </li>
                    <?php endif; endforeach; ?>
                    </ul>
                  </div>
                  </li>
          <?php } ?>
          </ul>            
         
        </div>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>