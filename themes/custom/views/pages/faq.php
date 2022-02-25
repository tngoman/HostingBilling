<div class="row">
        <div class="col-md-12">
             <?php blocks('full_width_top', get_slug()); ?>
     </div>
 </div>
  

<!-- container -->
<div class="container inner">
        <div class="row"> 

            <!-- main content -->
            <section class="col-md-9">

              <?php blocks('content_top', get_slug()); ?>

              <div class="faq"> 

              <?php//print_r($articles); ?>  

               <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                <?php foreach($articles as $key => $article) {?>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="heading<?=$key?>">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$key?>" aria-expanded="true" aria-controls="collapse<?=$key?>">
                                <i class="more-less glyphicon glyphicon-plus"></i>
                                <?=$article->title?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse<?=$key?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?=$key?>">
                        <div class="panel-body">
                        <?=$article->body?>
                        </div>
                    </div>
                </div>
              <?php } ?>
               

            </div><!-- panel-group -->              
              
            </div>

              <div class="inner">
               <?php blocks('content_bottom', get_slug()); ?>
            </div>
            
            </section>
            <!-- /main -->
 
            <aside class="col-sm-3 sidebar_right">
              
            <h3 class=""><?=lang('faq_categories')?></h3>  
                <ul class="list_group">
                    <?php $all = 0; foreach($categories as $category) { ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a
                            href="<?=base_url()?>faq/category/<?=strtolower(str_replace(' ', '_', $category->cat_name));?>">
                            <?=$category->cat_name;?></a>
                        <span class="badge badge-primary badge-pill"><?=$category->num;?></span>
                    </li>
                    <?php $all += $category->num;} ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a
                            href="<?=base_url()?>faq">
                            <?=lang('all')?></a>
                        <span class="badge badge-primary badge-pill"><?=$all;?></span>
                    </li>
                </ul> 
            </aside> 
            <!-- /Sidebar -->

        </div>
    </div>


     <!-- Full width -->   
    <section class="white-wrapper">
         <div class="row">
              <div class="col-md-12">
              <?php blocks('full_width_content_bottom', get_slug()); ?>
              </div>
            </div>
   </section>

 

 <!-- Normal width -->    
 <section class="whitesmoke-wrapper">	
      <div class="container inner">
        <div class="row">
          <div class="col-md-12">
          <?php blocks('page_bottom', get_slug()); ?>
          </div>
        </div>
      </div>
  </section>


<!-- Normal width -->  
  <section class="white-wrapper">
    <div class="container inner">
         <div class="row">
              <div class="col-md-12">
              <?php blocks('footer_top', get_slug()); ?>
              </div>
            </div>
        </div>
   </section>

 