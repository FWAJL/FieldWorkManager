<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<?php $version = "?v" . __VERSION_NUMBER__; ?>
<!DOCTYPE html>
<html lang="<?php echo $this->app->locale; ?>">
  <head>
    <meta charset="utf-8" />
    <title><?php echo $this->app->pageTitle; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->app->relative_path; ?>Web/css/app/reset.css<?php echo $version; ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->app->relative_path; ?>Web/css/core/bootstrap.css<?php echo $version; ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->app->relative_path; ?>Web/css/addons/toastr.css<?php echo $version; ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->app->relative_path; ?>Web/css/addons/jquery.contextMenu.css<?php echo $version; ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->app->relative_path; ?>Web/css/core/jquery-ui.css<?php echo $version; ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->app->relative_path; ?>Web/css/core/lightbox.css<?php echo $version; ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->app->relative_path; ?>Web/css/core/dropzone.css<?php echo $version; ?>" />

    <?php echo $this->app->globalResources["css_files"]; ?>
    
<!--    <script type="application/javascript" src="
<?php echo $this->app->relative_path; ?>
Web/js/app/controllers/pm_manager.js"></script>-->
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/core/jquery.js<?php echo $version; ?>"></script>
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/core/jquery-ui.js<?php echo $version; ?>"></script>
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/app/controllers/top_menu.actions.js<?php echo $version; ?>"></script>
    <?php echo $this->app->globalResources["js_files_head"]; ?>
    <!--<script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/app/controllers/pm_manager.js"></script>-->

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->app->relative_path; ?>Web/images/favicon.ico">-->
    <!--<link href='http://fonts.googleapis.com/css?family=Droid+Serif:700,400,400italic,700italic' rel='stylesheet' type='text/css'>-->

    <!--[if IE]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

  <body id="home">
