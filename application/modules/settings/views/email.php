<!-- START TEMPLATES -->
<?php
$template_group = isset($_GET['view']) ? $_GET['view']:'';
if($template_group == 'alerts'){
    $this->load->view($template_group);
}else{
    $this->load->view('email_settings');
}
?>