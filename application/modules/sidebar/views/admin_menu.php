<aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">

          <?php $up = count($updates); 
        $user = User::get_id();
        $user_email = User::login_info($user)->email; ?>

          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu" data-widget="tree">
              <li class="header"><?=lang('website_cms')?></li>
              <li><a href="<?=base_url()?>pages"><i class="fa fa-file-text-o"></i> <span><?=lang('pages')?></span></a>
              </li>
              <li><a href="<?=base_url()?>sliders"><i class="fa fa-photo"></i> <span><?=lang('sliders')?></span></a>
              </li>
              <li><a href="<?=base_url()?>blocks"><i class="fa fa-columns"></i> <span><?=lang('blocks')?></span></a>
              </li>
              <li><a href="<?=base_url()?>menus"><i class="fa fa-bars"></i> <span><?=lang('menus')?></span></a></li>
              <li><a href="<?=base_url()?>items/categories"><i class="fa fa-cube"></i>
                      <span><?=lang('categories')?></span></a></li>
              <li class="header"><?=lang('products_services')?></li>
              <li><a href="<?=base_url()?>items?view=hosting"><i class="fa fa-database"></i>
                      <span><?=lang('hosting_packages')?></span></a></li>              
              <li><a href="<?=base_url()?>items?view=service"><i class="fa fa-shopping-basket"></i>
                      <span><?=lang('products_services')?></span></a></li>
            <li><a href="<?=base_url()?>addons"><i class="fa fa-window-restore"></i>
                     <span><?=lang('addons')?></span></a></li>
              <li><a href="<?=base_url()?>promotions"><i class="fa fa-flag"></i>
                      <span><?=lang('promotions')?></span></a></li>                
              <li><a href="<?=base_url()?>servers"><i class="fa fa-server"></i> <span><?=lang('servers')?></span></a>
              </li>
              <li><a href="<?=base_url()?>items?view=domains"><i class="fa fa-list"></i>
                <span><?=lang('domain_pricing')?></span></a></li>
              <li><a href="<?=base_url()?>registrars"><i class="fa fa-globe"></i>
                      <span><?=lang('domain_registrars')?></span></a></li>
              <li class="header"><?=lang('login_as_client')?></li>
              <li><?php
			 $attributes = array('class' => 'bs-example form-horizontal');
        echo form_open(base_url().'profile/switch',$attributes); ?>
                  <div class="row" id="switch_user">
                      <div class="col-md-10">
                          <select class="chosen-select form-control" name="user_id" required>
                              <option value="" selected><?=lang('select')?></option>
                              <optgroup label="<?=lang('clients')?>">
                                  <?php $clients = $this->db->where(array('co_id >'=> 1))->order_by('company_name','ASC')->get('companies')->result();
                    foreach ($clients as $client): ?>
                                  <?php if(!is_numeric($client->primary_contact)) {continue;} ?>
                                  <option value="<?=$client->primary_contact?>"><?=ucfirst($client->company_name)?>
                                  </option>
                                  <?php endforeach;  ?>
                              </optgroup>
                          </select>
                      </div>
                      <div class="col-md-2">
                          <button class="btn btn-dark btn-block"><i class="fa fa-sign-in"></i></button>
                      </div>
                  </div>
                  </form>
              </li>
          </ul>
      </section>

</aside>