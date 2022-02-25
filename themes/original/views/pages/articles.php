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
            <div class="kb-popular"> 
              <?php blocks('content_top', get_slug()); ?>
              <h2>Articles</h2>
                <ul>
                        <?php foreach($articles as $article) { ?>
                        <li>
                        <a href="<?=base_url()?>knowledge/article/<?=$article->slug;?>"><?=$article->title;?></a>
                        <span class="pull-right"><i class="fa fa-eye"></i> <?=$article->views;?></span>
                        <div class="small">Last Updated: <?=explode(' ', $article->modified)[0];?> in <a
                                    href="<?=base_url()?>knowledge/category/<?=strtolower(str_replace(' ', '_', $article->cat_name));?>">
                                    <?=$article->cat_name;?></a></div>                            
                        </li>
                        <?php } ?>
                    </ul>
               </div>
            </section>
            <!-- /main -->

            <!-- Sidebar -->
     
            <aside class="col-sm-3 sidebar_right">
            <div class="kb-latest">              
                    <h3 class="">Latest Articles</h3>  
                       <ul>
                        <?php foreach($latest as $article) { ?>
                        <li>
                        <a href="<?=base_url()?>knowledge/article/<?=$article->slug;?>"><?=$article->title;?></a>                          
                        </li>
                        <?php } ?>
                    </ul>
                </div> 


                <h3 class="">Categories</h3>  
                <ul class="list_group">
                    <?php foreach($categories as $category) { ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a
                            href="<?=base_url()?>knowledge/category/<?=strtolower(str_replace(' ', '_', $category->cat_name));?>">
                            <?=$category->cat_name;?></a>
                        <span class="badge badge-primary badge-pill"><?=$category->num;?></span>
                    </li>
                    <?php } ?>
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

 