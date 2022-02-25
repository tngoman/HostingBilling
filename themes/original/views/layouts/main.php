<?php $section = $this->template->template['partials']; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <?php $favicon = config_item('site_favicon'); $ext = substr($favicon, -4); ?>
    <?php if ( $ext == '.ico') { ?>
    <link rel="shortcut icon" href="<?=theme_assets()?>images/<?=config_item('site_favicon')?>">
    <?php } ?>
    <?php if ($ext == '.png') { ?>
    <link rel="icon" type="image/png" href="<?=theme_assets()?>images/<?=config_item('site_favicon')?>">
    <?php } ?>
    <?php if ($ext == '.jpg' || $ext == 'jpeg') { ?>
    <link rel="icon" type="image/jpeg" href="<?=theme_assets()?>images/<?=config_item('site_favicon')?>">
    <?php } ?>
    <?php if (config_item('site_appleicon') != '') { ?>
    <link rel="apple-touch-icon" href="<?=theme_assets()?>images/<?=config_item('site_appleicon')?>" />
    <link rel="apple-touch-icon" sizes="72x72" href="<?=theme_assets()?>images/<?=config_item('site_appleicon')?>" />
    <link rel="apple-touch-icon" sizes="114x114" href="<?=theme_assets()?>images/<?=config_item('site_appleicon')?>" />
    <link rel="apple-touch-icon" sizes="144x144" href="<?=theme_assets()?>images/<?=config_item('site_appleicon')?>" />
    <?php } ?>
    
       
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?php echo $template['title']; ?></title> 
    <?php echo $template['metadata']; ?>


    <link rel="stylesheet" href="<?=theme_assets()?>css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="<?=theme_assets()?>css/datatables.min.css" type="text/css" />
    <link rel="stylesheet" href="<?=theme_assets()?>css/dataTables.bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="<?=theme_assets()?>css/font-awesome.min.css">
    <link rel="stylesheet" href="<?=theme_assets()?>css/reset.css" type="text/css" />
    <link rel="stylesheet" href="<?=theme_assets()?>css/core.css" type="text/css" />
    <link rel="stylesheet" href="<?=theme_assets()?>css/style.css" type="text/css" />
    <link rel="stylesheet" href="<?=theme_assets()?>css/pricing-table.css" type="text/css" />
    <link rel="stylesheet" href="<?=theme_assets()?>css/sweetalert.css" type="text/css" />

    <?php
            $family = 'Lato';
            $font = config_item('system_font');
            switch ($font) {
                    case "open_sans": $family="Open Sans";  echo "<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,latin-ext,greek-ext,cyrillic-ext' rel='stylesheet' type='text/css'>"; break;
                    case "open_sans_condensed": $family="Open Sans Condensed";  echo "<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
                    case "roboto": $family="Roboto";  echo "<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
                    case "roboto_condensed": $family="Roboto Condensed";  echo "<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
                    case "ubuntu": $family="Ubuntu";  echo "<link href='https://fonts.googleapis.com/css?family=Ubuntu:400,300,500,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
                    case "lato": $family="Lato";  echo "<link href='https://fonts.googleapis.com/css?family=Lato:100,300,400,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>"; break;
                    case "oxygen": $family="Oxygen";  echo "<link href='https://fonts.googleapis.com/css?family=Oxygen:400,300,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>"; break;
                    case "pt_sans": $family="PT Sans";  echo "<link href='https://fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
                    case "source_sans": $family="Source Sans Pro";  echo "<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
            }
            ?>
    <style type="text/css">
    body {
        font-family: '<?=$family?>';
    }
    </style>

    <!--[if lt IE 9]>
            <script src="js/ie/html5shiv.js" cache="false">
            </script>
            <script src="js/ie/respond.min.js" cache="false">
            </script>
            <script src="js/ie/excanvas.js" cache="false">
            </script> <![endif]-->
    <script src="<?=theme_assets()?>js/jquery.min.js"></script>
    <script>
    var base_url = '<?=base_url()?>';
    </script>
</head>

<body>

    <?=$section['header']; ?>

    <?php if (!$this->uri->segment(1)) { 
      
         } else { 
                include(active_theme().'/views/sections/page_header.php'); 
        } ?>

    <?=$template['body'];?>
    <?=$section['footer']; ?>
 
    <script src="<?=theme_assets()?>js/bootstrap.min.js"></script>
    <script src="<?=theme_assets()?>js/datatables/jquery.dataTables.min.js"></script>
    <script src="<?=theme_assets()?>js/datatables/dataTables.bootstrap.min.js"></script>
    <script src="<?=theme_assets()?>js/sweetalert.min.js"></script>
    <script src="<?=theme_assets()?>js/script.js"></script> 
 

</body>

</html>