        <?php echo form_open('pages/delete_multi'); ?>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="box">
                    <div class="box-body">
                        <button type="submit" onClick="javascript:return confirm('<?=lang('delete_confirm_msg')?>');"
                            class="btn btn-danger btn-sm delete_multi"><i class="fa fa-trash"></i>
                            <?=lang('delete_selected')?></button>
                        <a href="<?=base_url()?>pages/edit" class="btn btn-sm btn-success pull-right"><i
                                class="fa fa-plus"></i> <?=lang('new_page')?></a>
                        <table id="pages" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center no-padding no-margin" style="vertical-align: middle;">
                                        <div class="pretty info smooth">
                                            <input type="checkbox" name="checkAll" id="dt-select-all" value="1">
                                        </div>
                                    </th>
                                    <th><?=lang('title')?></th>
                                    <th><?=lang('type')?></th>
                                    <th><?=lang('status')?></th>
                                    <th><span class="nobr"><?=lang('action')?></span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($pages as $page): ?>
                                <tr>
                                    <td class="text-center"><?=post_id($page)?></td>
                                    <td><a href="<?=post_url($page)?>" target="_blank"><?=post_title($page)?></a></td>
                                    <td>
                                    <?php if($page->faq == 1) {echo '<span class="label label-default">'.lang('faq').'</span>';}?>
                                    <?php if($page->knowledge == 1) {echo '<span class="label label-default">'.lang('knowledgebase').'</span>';}?>
                                    <?php if($page->faq == 0 && $page->knowledge == 0 ) {echo '<span class="label label-default">'.lang('page').'</span>';}?>
                                     </td>
                                    <td><?=post_status($page) == 1 ? '<span class="label label-success" data-toggle="tooltip" data-title="'.lang("active").'">'.lang("active").'</span>': '<span class="label label-danger" data-toggle="tooltip" data-title="'.lang("hidden").'">'.lang("hidden").'</span>'?>
                                    </td>
                                    <td>
                                        <a href="<?=base_url('pages/edit/' . post_id($page))?>"
                                            class="btn btn-info btn-xs" data-toggle="tooltip"
                                            data-title="<?=lang('edit')?>"><i class="fa fa-pencil"></i>
                                            <?=lang('edit')?></a>
                                        <a onClick="javascript:return confirm('<?=lang('delete_confirm')?>');"
                                            href="<?=base_url('pages/delete/' . post_id($page))?>"
                                            class="btn btn-xs btn-danger" data-toggle="tooltip"
                                            data-title="<?=lang('delete')?>"><i class="fa fa-trash"></i>
                                            <?=lang('delete')?></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>