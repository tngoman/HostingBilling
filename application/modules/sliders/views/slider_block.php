
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                <?php foreach ($slider as $key => $row) { ?>
                    <li data-target="#carousel-example-generic" data-slide-to="<?=$key?> <?=($key == 0) ? 'active' : ''?> "></li> 
                    <?php }  ?>
                </ol>
                <div class="carousel-inner">

                <?php foreach ($slider as $key => $row) { ?>
                  <div class="item <?=($key == 0) ? 'active' : '';?>">
                    <img src="<?=base_url()?>resource/uploads/<?=$row->image?>"/>
                        <div class="carousel-caption">
                            <h3><?=$row->title?></h3>
                            <p><?=$row->description?></p>
                        </div>
                    </div> 
                 <?php }  ?>
 
                </div>
                <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span></a><a class="right carousel-control"
                        href="#carousel-example-generic" data-slide="next"><span class="glyphicon glyphicon-chevron-right">
                        </span></a>
            </div>
        
 