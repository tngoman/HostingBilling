<?php

/* Connect to SOAP Server */
$client = new SoapClient( null, 
         array( 'location' => $soap_url, 
                     'uri' => $soap_uri, 
              'exceptions' => 1, 
                   'trace' => false 
               )
           );
?>
