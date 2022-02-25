<ul <?=($menu->id == 1) ? 'class="nav navbar-nav"' : 'class="menu-'.$menu->id.'"'?>>
    <?php
                             
              for ($i = 0; $i < count($menu->main_menu, true); $i++) {

                    if(strpos($menu->main_menu[$i]->url, 'http') !== false){ 
                        $url = $menu->main_menu[$i]->url;
                    } else{
                        $url = ($menu->main_menu[$i]->url == '/') ? base_url() : base_url().$menu->main_menu[$i]->url;
                    } 

                   if (count($menu->main_menu[$i]->parent_menu, true) == 0): ?>
    <li><a href="<?=($url != '/') ? $url : base_url(); ?>">
            <?php echo
                              $menu->main_menu[$i]->title?></a></li>
    <?php else: ?>
    <li class="has-dropdown">
        <a href="<?php echo base_url() . $url ?>" class="dropdown-toggle" data-toggle="dropdown"><?php
                              echo $menu->main_menu[$i]->title ?></a>
        <ul class="dropdown-menu">
            <?php for ($b = 0; $b < count($menu->main_menu[$i]->parent_menu, true); $b++):

                            if(strpos($menu->main_menu[$i]->parent_menu[$b]->url, 'http') !== false){
                                    $url = $menu->main_menu[$i]->parent_menu[$b]->url;
                                } else{
                                    $url = base_url().$menu->main_menu[$i]->parent_menu[$b]->url;
                                } 

                                  if (!isset($menu->main_menu[$i]->parent_menu[$b]->parent_submenu)): ?>
            <li><a href="<?=$url?>"><?php echo
                                              $menu->main_menu[$i]->parent_menu[$b]->title ?></a></li>
            <?php else: ?>
            <li class="has-dropdown dropdown-submenu">
                <a href="<?php echo base_url() .
                                              $url ?>"><?php echo
                                              $menu->main_menu[$i]->parent_menu[$b]->title ?></a>
                <?php if (isset($menu->main_menu[$i]->parent_menu[$b]->parent_submenu)):
                                        ?>
                <ul class="dropdown-menu">
                    <?php foreach
                                            ($menu->main_menu[$i]->parent_menu[$b]->parent_submenu
                                            as $par_sub) :

                                            if(strpos($par_sub->url, 'http') !== false){
                                            $url = $par_sub->url;
                                            } else{
                                                $url = base_url().$par_sub->url;
                                            } 

                                            ?>
                    <li><a href="<?php echo
                                             $url ?>"><?php echo
                                             $par_sub->title ?>
                        </a></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

            </li>
            <?php endif; ?>
            <?php endfor; ?>
        </ul>
    </li>
    <?php endif; ?>
    <?php } ?>
</ul>