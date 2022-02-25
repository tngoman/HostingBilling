<?php $content = $content[0]; ?>
<div class="row">
    <!-- Form start -->
    <?=form_open_multipart()?>

    <div class="col-md-9 col-sm-12">
        <div class="box">
            <div class="box-body">
                <div class="form-group">
                    <label><?=lang('title')?></label>
                    <?php echo form_input('title', set_value('title', isset($content->title) ? $content->title : ''), array('class' => 'form-control', 'id' => 'titleInput')); ?>
                    <?php echo form_error('title', '<p class="text-danger">', '</p>'); ?>
                </div><!-- /.form-group -->
                <div class="form-group">
                    <label><?=lang('slug')?></label>
                    <?php echo form_input('slug', set_value('slug', isset($content->slug) ? $content->slug : ''), array('class' => 'form-control', 'id' => 'slugInput')); ?>
                    <?php echo form_error('slug', '<p class="text-danger">', '</p>'); ?>
                </div><!-- /.form-group -->
                <div class="form-group">
                    <label><?=lang('body')?></label>
                    <?php echo form_textarea('body', set_value('body', isset($content->body) ? $content->body : '', FALSE), array('class' => 'form-control foeditor', 'id' => 'body')); ?>
                    <?php echo form_error('body', '<p class="text-danger">', '</p>'); ?>
                </div><!-- /.form-group -->

                <hr>
                <div class="box collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?=lang('page_seo')?></h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">

                        <div class="form-group">
                            <label><?=lang('meta_title')?></label>
                            <?php echo form_input('meta_title', set_value('meta_title', isset($content->meta_title) ? $content->meta_title : ''), array('class' => 'form-control', 'placeholder' => lang('use_page_title'))); ?>
                            <?php echo form_error('meta_title', '<p class="text-danger">', '</p>'); ?>
                        </div><!-- /.form-group -->

                        <div class="form-group">
                            <label><?=lang('meta_desc')?></label>
                            <?php echo form_textarea(array(
                            'name' => 'meta_desc',
                            'id' => 'notes',
                            'value' => set_value(isset($content->meta_desc) ? $content->meta_desc : ''),
                            'rows' => '3',
                            'class' => 'form-control',
                            'placeholder' => lang('use_site_desc')
                        )); ?>
                            <?php echo form_error('meta_desc', '<p class="text-danger">', '</p>'); ?>
                        </div><!-- /.form-group -->
                    </div>
                </div>


            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col-md8 -->

    <div class="col-md-3 col-sm-12">


        <!-- page settings -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?=lang('page') . ' ' . lang('settings')?></h3>
                <div class="box-tools pull-right">
                    <!-- Buttons, labels, and many other things can be placed here! -->
                    <!-- Here is a label for example -->
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i
                            class="fa fa-minus"></i></button>
                </div><!-- /.box-tools -->
            </div><!-- /.box-header -->
            <div class="box-body">


                <div class="row">
                    <label class="col-md-6"><?=lang('publish')?></label>
                    <div class="col-md-6">
                        <label class="switch">
                            <input type="hidden" value="off" name="status" />
                            <input type="checkbox" <?php if(isset($content->status) && $content->status == 1){ echo "checked=\"checked\""; } ?>
                                name="status">
                            <span></span>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <label class="col-md-6"><?=lang('right_sidebar')?></label>
                    <div class="col-md-6">
                        <label class="switch">
                            <input type="hidden" value="off" name="sidebar_right" />
                            <input type="checkbox"
                                <?php if(isset($content->sidebar_right) && $content->sidebar_right == 1){ echo "checked=\"checked\""; } ?>
                                name="sidebar_right">
                            <span></span>
                        </label>
                    </div>
                </div>


                <div class="row">
                    <label class="col-md-6"><?=lang('left_sidebar')?></label>
                    <div class="col-md-6">
                        <label class="switch">
                            <input type="hidden" value="off" name="sidebar_left" />
                            <input type="checkbox"
                                <?php if(isset($content->sidebar_left) && $content->sidebar_left == 1){ echo "checked=\"checked\""; } ?>
                                name="sidebar_left">
                            <span></span>
                        </label>
                    </div>
                </div>



                <div class="row">
                    <label class="col-md-6"><?=lang('show_in_menu')?></label>
                    <div class="col-md-6">
                        <select name="menu" class="form-control">
                            <option value="0"><?=lang('none')?></option>
                            <?php  
                                    foreach($menu_groups AS $menu) { ?>
                            <option value="<?=$menu->id?>" <?=(isset($content->menu) && $menu->id == $content->menu) ? 'selected': '' ?>>
                                <?=$menu->title?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

            </div><!-- /.box-body -->
        </div><!-- /.box -->


        <div class="box collapsed-box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><?=lang('faq_block')?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i
                            class="fa fa-plus"></i></button>
                </div>
            </div>
            <div class="box-body">


                <div class="row">
                    <label class="col-md-6"><?=lang('display')?></label>
                    <div class="col-md-6">
                        <label class="switch">
                            <input type="hidden" value="off" name="faq" />
                            <input type="checkbox" <?php if(isset($content->faq) && $content->faq == 1){ echo "checked=\"checked\""; } ?>
                                name="faq">
                            <span></span>
                        </label>
                    </div>
                </div>


                <label><?=lang('category')?></label>
                <select name="faq_id" class="form-control">
                    <option value="0"><?=lang('none')?></option>
                    <?php
                        $categories = $this->db->where('parent', 6)->get('categories')->result();
                        if (!empty($categories)) {
                            foreach ($categories as $key => $c) {  ?>
                               <option value="<?=$c->id?>" <?=(isset($content->faq_id) && $c->id == $content->faq_id) ? 'selected': '' ?>>
                        <?=$c->cat_name?></option>
                      <?php } } ?>
                </select>

            </div><!-- /.box-body -->
        </div><!-- /.box -->




        <div class="box collapsed-box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title"><?=lang('knowledgebase')?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i
                            class="fa fa-plus"></i></button>
                </div>
            </div>
            <div class="box-body">


                <div class="row">
                    <label class="col-md-6"><?=lang('display')?></label>
                    <div class="col-md-6">
                        <label class="switch">
                            <input type="hidden" value="off" name="knowledge" />
                            <input type="checkbox" <?php if(isset($content->knowledge) && $content->knowledge == 1){ echo "checked=\"checked\""; } ?>
                                name="knowledge">
                            <span></span>
                        </label>
                    </div>
                </div>

                <label><?=lang('category')?></label>
                <select name="knowledge_id" class="form-control">
                    <option value="0"><?=lang('none')?></option>
                    <?php
                        $categories = $this->db->where('parent', 7)->get('categories')->result();
                        if (!empty($categories)) {
                            foreach ($categories as $key => $c) {  ?>
                               <option value="<?=$c->id?>" <?=(isset($content->knowledge_id) && $c->id == $content->knowledge_id) ? 'selected': '' ?>>
                        <?=$c->cat_name?></option>
                      <?php } } ?>
                </select>

                <br/>

                <div class="form-group">
                    <label>Video URL</label>
                    <?php echo form_input('video', set_value('video', isset($content->video) ? $content->video : ''), array('class' => 'form-control', 'placeholder' => 'https://www.youtube.com/embed/QJHqLJLQLQ8')); ?> 
                </div><!-- /.form-group -->

            </div><!-- /.box-body -->
        </div><!-- /.box -->



        <!-- Custom options -->
        <div class="box">


            <div class="box-body">
                <hr>
                <button type="submit" class="btn btn-success btn-block"><i class="fa fa-save"></i>
                    <?=lang('save')?></button>

            </div><!-- /.box-body -->
        </div><!-- /.box -->



    </div><!-- /.col-md-4 -->

    <!-- Form close -->
    <?=form_close()?>

</div><!-- /.row -->
<script>
$('#titleInput').on('keyup', function() {
    var path = $(this).val();
    path = path.replace(/ /g, "_").replace("/", "_").toLowerCase();
    $('#slugInput').val(path);
});
</script>