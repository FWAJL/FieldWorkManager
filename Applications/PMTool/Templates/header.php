<!DOCTYPE html>
<html lang="<?php echo $this->app->locale; ?>">
  <head>
    <meta charset="utf-8" />
    <title><?php echo $this->app->pageTitle; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->app->relative_path; ?>Web/css/app/reset.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->app->relative_path; ?>Web/css/app/styles.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->app->relative_path; ?>Web/css/core/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->app->relative_path; ?>Web/css/addons/toastr.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->app->relative_path; ?>Web/css/app/core-app-style.css" />
        
    <?php echo $this->app->globalResources["css_files"]; ?>
    <?php echo $this->app->globalResources["js_files_head"]; ?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    <!--<link href='http://fonts.googleapis.com/css?family=Droid+Serif:700,400,400italic,700italic' rel='stylesheet' type='text/css'>-->

    <!--[if IE]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

  <body id="home">
    <div id="wrapper">
      <figure class="logo"><img src="<?php echo $this->app->relative_path . $this->app->logoImageUrl; ?>"></figure>