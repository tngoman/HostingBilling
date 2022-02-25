<!DOCTYPE html>
<html lang="en" class="bg-dark">
<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?=base_url()?>resource/images/logo_favicon.png">
    <title>Hosting Billing Setup</title>
    <meta name="description" content="Hosting Billing is a Client Management and Invoicing System for Web Hosting businesses." />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="<?=base_url()?>resource/css/install.css" type="text/css" />
    <link rel="stylesheet" href="<?=base_url()?>resource/js/fuelux/fuelux.css" type="text/css" />
    <link rel="stylesheet" href="<?=base_url()?>resource/css/font-awesome.min.css">
    <!--[if lt IE 9]>
    <script src="js/ie/html5shiv.js" cache="false">
    </script>
    <script src="js/ie/respond.min.js" cache="false">
    </script>
    <script src="js/ie/excanvas.js" cache="false">
    </script> <![endif]-->
</head>
<body>

<!--main content start-->
<section id="content" class="m-t-lg wrapper-md animated fadeInUp">

    <div class="container" style="width:60%">
        <section class="panel panel-default bg-white m-t-lg">
            <header class="panel-heading text-center">
                <strong>Hosting Billing Setup</strong>
            </header>

            <div class = "panel-body wrapper-lg">

                <?php
                $step1 = $step2 = $step3 = $step4 = '';
                $badge1 = $badge2 = $badge3 = $badge4 ='badge';
                if(isset($_GET['step'])){
                    switch ($_GET['step']) {
                        case '2':
                            $step2 = 'active'; $badge2='badge badge-success';
                            break;
                        case '3':
                            $step3 = 'active'; $badge3='badge badge-success';
                            break;
                        case '4':
                            $step4 = 'active'; $badge4='badge badge-success';
                            break;

                        default:
                            $step1 = 'active'; $badge1='badge badge-success';
                            break;
                    }
                }else $step1 = 'active'; $badge1='badge';
                ?>


                <div class="panel panel-default wizard">
                    <div class="wizard-steps clearfix" id="form-wizard">
                        <ul class="steps">
                            <li class="<?=$step1?>"><span class="<?=$badge1?>">1</span>System Check</li>
                            <li class="<?=$step2?>"><span class="<?=$badge2?>">2</span>Database Settings</li>
                            <li class="<?=$step3?>"><span class="<?=$badge3?>">3</span>Install</li>
                            <li class="<?=$step4?>"><span class="<?=$badge4?>">4</span>Basic Settings</li>
                        </ul>
                    </div>
                    <div class="step-content clearfix" style="background-color: #fff;">

                        <?php
                        if($this->session->flashdata('message')){ ?>
                            <div class="alert alert-info">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <i class="fa fa-info-sign"></i><?=$this->session->flashdata('message')?>
                            </div>
                        <?php } ?>

                        <div class="step-pane <?=$step1?>" id="step1">


                            <?php
                            $config_file = "./application/config/config.php";
                            $database_file = "./application/config/database.php";
                            $autoload_file = "./application/config/autoload.php";
                            $route_file = "./application/config/routes.php";
                            $htaccess_file = ".htaccess";
                            $error = FALSE;
                            ?>

                            <div class="row">
                            <div class="col-lg-6">
                                <?php
                                    if(phpversion() < "5.3"){ $error = TRUE;
                                        echo "<div class='alert alert-danger'>Your PHP version is ".phpversion()."! PHP 5.3 or higher required!</div>"; }else{
                                        echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> You are running PHP ".phpversion()."</div>";
                                    } 

                                 if(!extension_loaded('mysqli')){$error = TRUE; echo "<div class='alert alert-danger'>Mysqli PHP extension missing!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Mysqli PHP extension loaded!</div>";}

                                 if(!extension_loaded('imap')){$error = TRUE; echo "<div class='alert alert-danger'>IMAP PHP extension missing!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> IMAP PHP extension loaded!</div>";}
     
                                 if(!extension_loaded('mbstring')){$error = TRUE; echo "<div class='alert alert-danger'>MBString PHP extension missing!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> MBString PHP extension loaded!</div>";}
     
                                 if(!extension_loaded('zip')){$error = TRUE; echo "<div class='alert alert-danger'>ZIP PHP extension missing!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> ZIP PHP extension loaded!</div>";}
     
                                 if(!extension_loaded('gd')){echo "<div class='alert alert-danger'>GD PHP extension missing!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> GD PHP extension loaded!</div>";}
                                 
                                 if(!extension_loaded('pdo')){$error = TRUE; echo "<div class='alert alert-danger'>PDO PHP extension missing!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> PDO PHP extension loaded!</div>";}
                                    
                                 ?>
                            </div>
                            <div class="col-lg-6">
                                    <?php
                                        if(!extension_loaded('curl')){$error = TRUE; echo "<div class='alert alert-danger'>CURL PHP extension missing!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> CURL PHP extension loaded!</div>";}
                                        if(!is_writeable($database_file)){$error = TRUE; echo "<div class='alert alert-danger'>Database File (application/config/database.php) is not writeable!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Database file is writeable!</div>";}
                                        if(!is_writeable($config_file)){$error = TRUE; echo "<div class='alert alert-danger'>Config File (application/config/config.php) is not writeable!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Config file is writeable!</div>";}
                                        if(!is_writeable($route_file)){$error = TRUE; echo "<div class='alert alert-danger'>Route File (application/config/routes.php) is not writeable!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Routes file is writeable!</div>";}
                                        if(!is_writeable($autoload_file)){$error = TRUE; echo "<div class='alert alert-danger'>Autoload File (application/config/autoload.php) is not writeable!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Autoload file is writeable!</div>";}
                                        if(!is_writeable($htaccess_file)){$error = TRUE; echo "<div class='alert alert-danger'>HTACCESS File (.htaccess) is not writeable!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> HTACCESS file is writeable!</div>";}
                                        if(!is_writeable("./resource/tmp")){echo "<div class='alert alert-danger'><i class='fa fa-times'></i> /resource/tmp folder is not writeable!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> /resource/tmp folder is writeable!</div>";}
                                     ?>
                            </div>

                           </div>

                            <div class="actions pull-right">
                                <a href="<?php echo base_url()?><?=config_item('index_page')?>/installer/start" class="btn btn-danger">Next</a>
                            </div>

                        </div>

                        <div class="step-pane <?=$step2?>" id="step2">
        <?php
             $attributes = array('class' => 'm-b-sm form-horizontal','id' => 'database','novalidate' => 'novalidate');
          echo form_open(base_url().config_item('index_page').'/installer/db_setup',$attributes); ?>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Database Host</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control"  placeholder="localhost" name="set_hostname">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Database Name</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" name="set_database">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Database Username</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" name="set_db_user">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Database Password</label>
                                    <div class="col-lg-3">
                                        <input type="password" class="form-control" name="set_db_pass">
                                    </div>
                                </div>
 

                                <div class="actions pull-left">
                                    <a href="<?php echo base_url()?><?=config_item('index_page')?>/installer" class="btn btn-danger btn-sm">Previous</a>
                                    <button type="submit" class="btn btn-danger btn-sm">Next</button>
                                </div>

                            </form>
                        </div>



                        <div class="step-pane <?=$step3?>" id="step3">
                        <h4>Ready to install</h4>
                        <hr>
                        <?php
                        $attributes = array('class' => 'm-b-sm form-horizontal','id' => 'verify','novalidate'=>'novalidate');
                        echo form_open(base_url().config_item('index_page').'/installer/install',$attributes); ?>
                   
                              <button type="submit" class="btn btn-success btn-lg btn-block">Install</button>

                            </form>
                            <div class="actions pull-left">
                                    <a href="<?php echo base_url()?><?=config_item('index_page')?>/installer" class="btn btn-danger btn-sm">Previous</a>
                            </div>

                        </div>




                        <div class="step-pane <?=$step4?>" id="step4">

        <?php
             $attributes = array('class' => 'm-b-sm form-horizontal','id' => 'complete','novalidate'=>'novalidate');
          echo form_open(base_url().config_item('index_page').'/installer/complete',$attributes); ?>

                                <?php
                                $base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
                                $base_url .= "://".$_SERVER['HTTP_HOST'];
                                $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

                                ?>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Company Domain</label>
                                    <div class="col-lg-7">
                                        <input type="text" class="form-control" value="<?=$base_url?>" name="set_base_url">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Full Name</label>
                                    <div class="col-lg-7">
                                        <input type="text" class="form-control" name="set_admin_fullname">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Admin Username</label>
                                    <div class="col-lg-7">
                                        <input type="text" class="form-control" name="set_admin_username">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Admin Password</label>
                                    <div class="col-lg-7">
                                        <input type="password" class="form-control" name="set_admin_pass">
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Admin Email</label>
                                    <div class="col-lg-7">
                                        <input type="email" class="form-control" name="set_admin_email">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Company Name</label>
                                    <div class="col-lg-7">
                                        <input type="text" class="form-control" name="set_company_name">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Company Email</label>
                                    <div class="col-lg-7">
                                        <input type="email" class="form-control" name="set_company_email">
                                    </div>
                                </div>


                                <div class="actions pull-left">
                                    <button type="submit" class="btn btn-danger btn-sm">Complete</button>
                                </div>

                            </form>

                        </div>





                    </div>
                </div>

            </div>
        </section>
    </div>
</section>
<!--main content end-->
<script src="<?=base_url()?>resource/js/jquery.min.js"></script>
<script src="<?=base_url()?>resource/js/app.js"></script>
<script src="<?=base_url()?>resource/js/jquery.validate.min.js"></script>

<script>
    $(function() {
        $("#database").validate({
            rules: {
                set_hostname: "required",
                set_database: "required",
                set_db_user: "required"
            },

            // Specify the validation error messages
            messages: {
                set_hostname: "Please enter your hostname usually localhost",
                set_database: "Please specify your database name",
                set_db_user: "Please specify your database username"
            },

            submitHandler: function(form) {
                form.submit();
            }
        });

        $("#verify").validate({
            rules: {
                set_envato_license: "required",
            },

            // Specify the validation error messages
            messages: {
                set_envato_license: "Enter your envato purchase code here"
            },

            submitHandler: function(form) {
                form.submit();
            }
        });

        $("#complete").validate({
            rules: {
                set_admin_username: "required",
                set_admin_fullname: "required",
                set_admin_pass: "required",
                set_admin_email: {
                    required: true,
                    email: true
                },
                set_company_name: "required",
                set_company_email: {
                    required: true,
                    email: true
                },
            },

            // Specify the validation error messages
            messages: {
                set_admin_username: "Please enter admin username",
                set_admin_fullname: "Set your admin full name",
                set_admin_pass: "Set your admin password",
                set_admin_email: "Set admin email address",
                set_company_name: "Set your company name",
                set_company_email: "Enter your company email address e.g info@domain.com",
            },

            submitHandler: function(form) {
                form.submit();
            }
        });

    });

</script>




<!-- Bootstrap -->
<!-- App -->
</body>
</html>
