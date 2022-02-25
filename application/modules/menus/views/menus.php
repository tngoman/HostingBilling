<div class="box" id="menus">
    <div class="box-header">
    <ul id="menu-group">
            <?php foreach ($menu_groups as $menu) : ?>
                <li id="group-<?php echo $menu->id; ?>">
                    <a class="btn btn-info" href="<?php echo site_url('menus/menu'); ?>/<?php echo $menu->id; ?>">
                        <?php echo $menu->title; ?>
                    </a>
                </li>
            <?php endforeach; ?>
            <li id="add-group"><a href="<?php echo site_url('menus/add_menu'); ?>"
                                    title="Add New Menu" class="btn btn-success"><?=lang('add_menu')?></a>
            </li>
        </ul>
</div>
  <div class="box-body">
 
            <div id="row"> 
                    <div class="col-md-9">
                         

                        <form method="post" id="form-menu" action="<?php echo site_url('menus/save_position'); ?>">
                    
                            <?php echo $menu_ul; ?>
                            <div id="ns-footer">
                                <button type="submit" class="btn btn-success" id="btn-save-menu">Save
                                    Menu
                                </button>
                            </div>
                            <br>
                        </form>
                    </div>
                    <aside class="col-md-3 col-sm-12">

                    <section class="box">
                        <div class="box-body">
                            <h4>Add Menu Item</h2>
                            <div>
                                <form id="form-add-menu" method="post" action="<?php echo site_url('menus/add'); ?>">
                                    <div class="form-group">
                                        <label for="menu-title">Title</label>
                                        <input style="width: 100% !important;" type="text" name="title" required
                                               id="menu-title"
                                               class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="menu-url">URL</label>
                                        <input type="text" name="url" id="menu-url" class="form-control" required
                                               style="width: 100% !important;">
                                    </div>
                                    <p class="buttons">
                                        <input type="hidden" name="group_id" value="<?php echo $group_id; ?>">
                                        <button id="add-menu" type="submit" class="btn btn-primary btn-sm">Add Menu Item
                                        </button>
                                    </p>
                                </form>
                            </div>
                            </div>
                        </section>


                    <section class="box">
                    <div class="box-body">
                            <h2><?php echo $group_title; ?></h2>
                            <div> 
                            <form method="post"  action="<?php echo site_url('menus/edit_menu'); ?>">
                            <span id="edit-group-input"><?php echo $group_title; ?></span>
                                <input type="hidden" name="id" value="<?php echo $group_id; ?>">
                                <div class="edit-group-buttons">
                                <button id="submit_menu" class="btn btn-sm btn-success" type="submit"><?=lang('save')?></button>
                                    <a id="edit-group" href="#" title="Edit Menu"><span class="btn btn-primary btn-sm">Edit</span></a>
                                    <?php if ($group_id > 1) : ?>
                                        <a id="delete-group" href="#"><span class="btn btn-danger btn-sm">Delete</span></a>
                                    <?php endif; ?>
                                </div>
                                </form>
                            </div>
                            </div>
                        </section>
  
                        
                      
                    </aside>
                    
        
            </div>
            <div id="loading"></div>
        </div>
    </div>
 