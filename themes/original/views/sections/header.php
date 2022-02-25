<div class="content-wrap">
	<div id="home" class="body-wrapper"> 
		<header class="full-header">
			<div id="top-bar" class="top-bar">
				<div class="container">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6 hidden-xs">
            			<?php blocks('header_top_left', get_slug()); ?>
						</div>
						<!-- .col-md-6 end -->
						<div class="col-xs-12 col-sm-6 col-md-6 text-right">
            			<?php blocks('header_top_right', get_slug()); ?>							
						</div>
						<!-- .col-md-6 end -->
					</div>
				</div>
			</div>
			<nav id="primary-menu" class="navbar style-1">
				<div class="row">
					<div class="container mobile-heading">
						<!-- Brand and toggle get grouped for better mobile display -->
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a class="logo" href="/">
              <img src="<?=base_url()?>resource/images/<?=config_item('company_logo')?>"  />    
							</a>
						</div>  


						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse pull-right" id="bs-example-navbar-collapse-1">
						<?php blocks('main_menu', get_slug()); ?>
						</div>
						<!-- /.navbar-collapse -->
					</div>
					<!-- /.container-fluid -->
				</div>
			</nav>
		</header>
    <!-- End  Header -->
    

 
<div class="floating">
<div class="icon-bar">
<a href="<?=base_url()?>cart/shopping_cart"><?php blocks('floating_icon', get_slug()); ?></a> 
</div>
</div>