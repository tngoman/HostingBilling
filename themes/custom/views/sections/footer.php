		<!-- Start Footer -->
		<footer id="footer" class="site-footer">
			<div id="template-footer" role="contentinfo">
				<div class="container">
					<div class="row">
						<div class="col-md-4">
                        <?php blocks('footer_left', get_slug()); ?>							
						</div>

						<div class="col-md-4">
                        <?php blocks('footer_center', get_slug()); ?> 
						</div>

						<div class="col-md-4 quick-contact">
                        <?php blocks('footer_right', get_slug()); ?>							
						</div>

					</div>
				</div>

			<div style="text-align:center;"> <?=config_item('company_name')?> &copy; <?=date('Y')?></div>
			</div>
		</footer>
		<!-- End Footer  -->

 


	</div>

	<!-- Back to top Link -->
	<div id="to-top" class="main-bg"><span class="fa fa-chevron-up"></span></div>

</div>
 