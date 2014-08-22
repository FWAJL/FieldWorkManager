<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/core/parsexml.js"></script>
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/core/ko.js"></script>
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/core/bootstrap.min.js"></script>  
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/addons/toastr.js"></script>  
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/app/services/config.js"></script>
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/app/services/dataservice.js"></script>
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/app/services/utils.js"></script>
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/app/design/breadcrumb.js"></script>
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/app/controllers/tabs.js"></script>
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/app/debugger.js"></script>  
    <?php echo $this->app->globalResources["js_files_html"]; ?>
  </body>
</html>
