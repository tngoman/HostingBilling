<?php $page = $this->template->content; ?>

<div class="front-page">

    <div class="row">
        <div class="col-md-12">
            <?php blocks('full_width_top', get_slug()); ?>
        </div>
    </div>


    <!-- container -->
    <div class="container inner">
        <div class="row">

            <!-- Sidebar -->
            <?php if($page->sidebar_left == 1) { ?>
            <aside class="col-sm-3 sidebar_left">
                <?php blocks('sidebar_left', get_slug()); ?>
            </aside>
            <?php } ?>
            <!-- /Sidebar -->

            <!-- main content -->
            <section class="<?php 
                if($page->sidebar_right == 1 && $page->sidebar_left == 1) { echo 'col-md-6'; }
                else if($page->sidebar_right == 1 || $page->sidebar_left == 1) { echo 'col-md-9'; }
                else { echo 'col-md-12 0'; } 
                ?>">

                <?php blocks('content_top', get_slug()); ?>

                <?=$page->body; ?>

                <div class="inner">
                    <?php blocks('content_bottom', get_slug()); ?>
                </div>

            </section>
            <!-- /main -->

            <!-- Sidebar -->
            <?php if($page->sidebar_right == 1) { ?>
            <aside class="col-sm-3 sidebar_right">
                <?php blocks('sidebar_right', get_slug()); ?>
            </aside>
            <?php } ?>
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

</div>