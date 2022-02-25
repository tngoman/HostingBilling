              <?php if(!$this->uri->segment(3)){ ?>
              <div class="btn-group">

              <button class="btn btn-<?=config_item('theme_color');?> btn-sm"><?=lang('year')?></button>
              <button class="btn btn-<?=config_item('theme_color');?> btn-sm dropdown-toggle" data-toggle="dropdown"><span class="caret"></span>
              </button>

              <ul class="dropdown-menu">
              <?php
                      $max = date('Y');
                      $min = $max - 3;
                      foreach (range($min, $max) as $year) { ?>
                    <li><a href="<?=base_url()?>reports?setyear=<?=$year?>"><?=$year?></a></li>
              <?php }
              ?>
                        
              </ul>

              </div>
              <?php } ?>