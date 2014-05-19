<?php 
$title_site = $this->app->i8n->getCommonResource("header","title_site"); 

$menu_about_text = $this->app->i8n->getCommonResource("menu","menu_about_text"); 
$menu_resume_text = $this->app->i8n->getCommonResource("menu","menu_resume_text"); 
$menu_contact_text = $this->app->i8n->getCommonResource("menu","menu_contact_text"); 
$menu_blog_text = $this->app->i8n->getCommonResource("menu","menu_blog_text"); 

?>
<html lang="en"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Jérémie Litzler Personal site">
    <meta name="author" content="Jérémie Litzler">
    <link rel="shortcut icon" href="Web/images/favicon.ico">

    <title><?php echo $title_site; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="Web/css/bootstrap.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="Web/css/bootstrap-theme.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="Web/css/theme.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  <style id="holderjs-style" type="text/css"></style></head>

  <body role="document" style="">
    <!-- Fixed navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top navbar-light" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="home"><?php echo $title_site; ?></a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="about"><?php echo $menu_about_text; ?></a></li>
            <li><a href="resume"><?php echo $menu_resume_text; ?></a></li>
            <li><a href="contact"><?php echo $menu_contact_text; ?></a></li>
            <li><a href="http://jeremielitzler.net/Blog/"><?php echo $menu_blog_text; ?></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>