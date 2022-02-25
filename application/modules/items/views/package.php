<?php
$item = Item::view_item($id);
$list = array();
$server = (object) array();

foreach($servers as $srv) {
	 $list[$srv->type] = ucfirst($srv->type);
	 
	 if($srv->id == $item->server)
	 {
		$server = $srv;
	 }
}

?>

<div class="box">

    <div class="box-body">
        <div class="row">
            <div class="col-md-6">

                <?php 

				echo $this->settings->open_form(
					array('action' => '', 'id' => 'servers', 'method' => 'GET'));   
				
				$options = array(
					'label' => 'Server',
					'id' => 'server',
					'type' => 'dropdown',
					'options' => $list
				);

				if(isset($server->type))
				{
					$options['value'] = $server->type;
				}
				
				if(isset($_GET['server'])) {

					$options['value'] = $_GET['server'];
					foreach($servers as $srv) { 
						
						if($srv->type == $_GET['server']) 
						{
							$server = $srv;
						} 
					}
				}

				if(!isset($server->type) && !isset($_GET['server']))
				{
					$list = array_merge(array('none' => 'None'), $list); 
					$options = array(
						'label' => 'Server',
						'id' => 'server',
						'type' => 'dropdown',
						'options' => $list
					);
	 
					$options['value'] = 'none'; 
				}

				echo $this->settings->build_form_horizontal(array($options));
				echo $this->settings->close_form();


				if(isset($_GET['server']) || isset($server->type) && $server->type != '') {
		
					echo $this->settings->open_form(array('action' => ''));  

					if(isset($server->type) && $server->type != '') {
						$conf = $server->type;
					} 

					if(isset($_GET['server'])) {
						$conf = $_GET['server'];
					}
					
					$package_config = unserialize($item->package_config);
					if(is_array($package_config)) {
						$package_config['package'] = $item->package_name;
					}

					else {
						$package_config = array('package' => $item->package_name);
					}					

					$configuration = modules::run($conf.'/'.$conf.'_package_config', $package_config);		

					$configuration[] =  array(
						'id' => 'item_id',
						'type' => 'hidden',
						'value' => $id
					);

					$configuration[] =  array(
						'id' => 'server_id',
						'type' => 'hidden',
						'value' => $server->id
					);

					$configuration[] =  array(
						'id' => 'submit',
						'type' => 'submit',
						'label' => 'Save'
					);

					echo $this->settings->build_form_horizontal($configuration);
					echo $this->settings->close_form();
				}				 
			  ?>

            </div>
        </div>
    </div>
</div>

<script>
	$('#server').on('change', function() {
		$('#servers').submit();
	});
</script>