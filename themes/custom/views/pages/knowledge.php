    <div class="kb_header">
    <div class="row">      
    <div class="col-md-6 col-md-offset-3 inner">
            <h1>How can we help you?</h1>
            <h4>Use the box below to search for your issue.</h4>
            <div class="form-group form-group-lg m-t-5">
                <div class="input-group">
                    <input class="form-control auto-complete-off-processed src-input" name="txt_search"
                        id="kb_search" placeholder="Search" autocomplete="off" type="search">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                </div>
                <ul id="searchResult"></ul> 
                <div class="clear"></div> <div id="userDetail"></div> 
            </div>
        </div>     
    </div> 


    <!-- container -->
    <div class="container inner" id="kb">
        <div class="row">

            <!-- main content -->
            <section class="col-md-9 ">
   
                <div class="kb-popular">              
                    <h2 class="">Popular Articles</h2>  
                    <ul>
                        <?php foreach($popular as $article) { ?>
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


                <div class="masonry">
                    <?php  
                     foreach ($articles as $category) { 
                         if(!empty($category)) { ?>
                    <div class="kb-item">
                        <h2><?=$category[0]->cat_name;?> </h2>
                        <ul>
                            <?php foreach($category as $article) { ?>
                            <li><a href="<?=base_url()?>knowledge/article/<?=$article->slug;?>"><?=$article->title;?></a>
                                <div class="small">Last Updated: <?=explode(' ', $article->modified)[0];?></div>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php  } }  ?>
                    <div>
            </section>

            <aside class="col-sm-3">             
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

        </div>
    </div>