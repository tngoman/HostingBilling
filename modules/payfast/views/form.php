<div class="box">
    <div class="box-body">
	<div class="container">
        <div class="row">
        <div class="col-md-5">  

		<section class="panel panel-default bg-white m-t-lg radius_3">
                    <header class="panel-heading text-center">	
						<h3><?=lang('invoice'). " ".$info['item_name']; ?></h3>
					</header>
					<div class="panel-body">
						
					<?php $url = ($sandbox == 'TRUE') ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';?>
					<form action="https://<?php echo $url;?>/eng/process" class="content-center" method="post" id="pf_form" name="pf_form">
						
						<p>
						<img src="<?=base_url()?>resource/images/payfast_logo.png" style="width:300px;" />
						</p>

						<?php
						$html = '';
						$string = '';
					
						$data  = array(
							'merchant_id' => $merchant_id,
							'merchant_key' => $merchant_key,
							'return_url' => base_url().'invoices/view/'.$info['item_number'],
							'cancel_url' => base_url().'payfast/cancel',
							'notify_url' => base_url().'payfast/processed_ipn',
							'name_first' => $info['company_name'],
							'name_last' => $info['company_ref'],
							'email_address' => $info['company_email'],
							'm_payment_id' => $info['item_number'],
							'amount' => $info['amount'],
							'item_name' => $info['item_name'],							
							'item_description' => config_item('company_name'),
							'custom_int1' => $info['company_id']
							);   

					 
						$pfOutput = '';
						foreach( $data as $key => $val )
						{
							if(!empty($val))
							{
								$pfOutput .= $key .'='. urlencode( trim( $val ) ) .'&';
							}
						}						
						
						$getString = substr( $pfOutput, 0, -1 ); 
						$passPhrase = $passphrase;

						if(!empty( $passPhrase ) )
						{
							$getString .= '&passphrase='. urlencode( trim( $passPhrase ) );
						}   
						$data['signature'] = md5( $getString );
						
						foreach($data as $name=> $value)
						{ 
							$html .= '<input name="'.$name.'" type="hidden" value="'.$value.'" />'; 
						} 

						print $html;
						?>
				<hr>
				<span class="pull-left"><strong>Amount Due: R<?=$info['amount']?></strong></span>
				<input type="submit" value="Continue to Payfast" class="btn btn-success pull-right">
			</form>		 
         
            </section>
		 </div>
		</div>
        </div>        
    </div>                     
</div>

 