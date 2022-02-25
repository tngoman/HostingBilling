<!DOCTYPE html>
<html lang="en" class="bg-dark">
<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?=base_url()?>resource/images/logo_favicon.png">
    <title>Hosting Billing Update</title>
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
                <strong>Hosting Billing Update</strong>
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
                            <li class="<?=$step2?>"><span class="<?=$badge2?>">2</span>Verify Purchase</li>
                            <li class="<?=$step3?>"><span class="<?=$badge3?>">3</span>Install Updates</li>
                 
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
                            $route_file = "./application/config/routes.php"; 
                            $error = FALSE;
                            ?>

                            <div class="row">
                  
                            <div class="col-lg-12">
                                    <?php                                      
                                        if(!is_file('./application/config/installed.txt')){$error = TRUE; echo "<div class='alert alert-danger'>Hosting Billing installation not found!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Hosting Billing is installed!</div>";} 
                                        if(!is_writeable($route_file)){$error = TRUE; echo "<div class='alert alert-danger'>Route File (application/config/routes.php) is not writeable!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Routes file is writeable!</div>";}
                                        if(!is_writeable($config_file)){$error = TRUE; echo "<div class='alert alert-danger'>Config File (application/config/config.php) is not writeable!</div>";}else{echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Config file is writeable!</div>";}
                                     ?>
                            </div>

                           </div>

                            <div class="actions pull-right">
                                <a href="<?php echo base_url()?><?=config_item('index_page')?>/update/?step=2" class="btn btn-danger">Next</a>
                            </div>
                        </div>

                


                        <div class="step-pane <?=$step2?>" id="step2">

                        <?php
                        $attributes = array('class' => 'm-b-sm form-horizontal','id' => 'verify','novalidate'=>'novalidate');
                        echo form_open(base_url().config_item('index_page').'/update/verify',$attributes); ?>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Envato Username</label>
                                    <div class="col-lg-7">
                                        <input type="text" class="form-control" placeholder="hostingbilling" name="set_envato_user">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Purchase Code</label>
                                    <div class="col-lg-7">
                                        <input type="text" class="form-control" placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" name="set_envato_license">
                                        <span class="help-block m-b-none">Your purchase code from Envato <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Can-I-Find-my-Purchase-Code-" target="_blank">Read More</a></span>
                                    </div>
                                </div>

                                <div class="actions pull-right">
                                    <button type="submit" class="btn btn-danger">Next</button>
                                </div>

                            </form>

                        </div>




                        <div class="step-pane <?=$step3?>" id="step3">

                        <?php
                        $attributes = array('class' => 'form-horizontal' );
                        echo form_open(base_url().config_item('index_page').'/update/install',$attributes); ?>
                            
                                <h3>Select update option</h3>

                                <small>Your data will not be lost.</small>

                                <hr>

                                <div class="form-group">
                                    &nbsp;  <input type="radio" name="update" value="simple" required /> Update to Latest Version (<strong>v1.2 to v1.3</strong>)
                                </div>


                                <div class="form-group">
                                    &nbsp; <input type="radio" name="update" value="incremental" /> Install Updates for latest version (<strong>For v1.3 users only</strong>) 
                                </div>

                                <p>Please refresh your website after installing updates</p>

                                <div class="actions">
                                    <button type="submit" class="btn btn-danger">Next</button>
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
 


<!-- Bootstrap -->
<!-- App -->
</body>
</html>
