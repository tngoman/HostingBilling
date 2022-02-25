<?php

if ( $soapsecure == 'on' ) {
        
$soap_url = 'https://' . $soapsvrurl.':'.$soapsvrport . '/remote/index.php';
$soap_uri = 'https://' . $soapsvrurl.':'.$soapsvrport . '/remote/';
        
} else {
        
$soap_url = 'http://' . $soapsvrurl.':'.$soapsvrport . '/remote/index.php';
$soap_uri = 'http://' . $soapsvrurl.':'.$soapsvrport . '/remote/';
        
}

?>
