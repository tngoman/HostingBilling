    <div class="box-body">
        <h4 class="subheader text-muted h3"><?=lang('files')?>

        <a href="<?=base_url()?>companies/file/add/<?=$i->co_id?>" class="btn btn-<?=config_item('theme_color');?> btn-xs pull-right" data-toggle="ajaxModal" data-placement="left" title="<?=lang('upload_file')?>">
            <i class="fa fa-plus-circle"></i> <?=lang('upload_file')?></a>
        </h4>

        <ul class="list-unstyled p-files">
        <?php $this->load->helper('file');
          foreach (Client::has_files($i->co_id) as $key => $f) {
              $icon = $this->applib->file_icon($f->ext);
              $real_url = base_url().'resource/uploads/'.$f->file_name;
              ?>
            <div class="line"></div>
                <li>
                  <?php if ($f->is_image == 1) : ?>
                      <?php if ($f->image_width > $f->image_height) {
                          $ratio = round(((($f->image_width - $f->image_height) / 2) / $f->image_width) * 100);
                          $style = 'height:100%; margin-left: -'.$ratio.'%';
                      } else {
                          $ratio = round(((($f->image_height - $f->image_width) / 2) / $f->image_height) * 100);
                          $style = 'width:100%; margin-top: -'.$ratio.'%';
                      }  ?>
        <div class="file-icon icon-small">
            <a href="<?=base_url()?>companies/file/<?=$f->file_id?>"><img style="<?=$style?>" src="<?=$real_url?>" /></a>
        </div>
        <?php else : ?>
        <div class="file-icon icon-small"><i class="fa <?=$icon?> fa-lg"></i></div>
        <?php endif; ?>

        <a data-toggle="tooltip" data-placement="right" data-original-title="<?=$f->description?>" class="text-muted" href="<?=base_url()?>companies/file/<?=$f->file_id?>">
                      <?=(empty($f->title) ? $f->file_name : $f->title)?>
        </a>

        <div class="pull-right">

        <a href="<?=base_url()?>companies/file/delete/<?=$f->file_id?>" data-toggle="ajaxModal"><i class="fa fa-trash-o text-danger"></i>
        </a>

        </div>

        </li>


                <?php } ?>
            </ul>
 
</div>
<!-- End File section -->
