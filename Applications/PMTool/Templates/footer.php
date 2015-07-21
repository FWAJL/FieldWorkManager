<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
</div><!-- END ROW DIV -->
<div class="row">
  <div class="col-xs-7 col-pad-5"><p class="poweredby"><?php echo __POWEREDBY__; ?></p></div>
  <div class="col-xs-5 col-pad-5">
    <p class="version"><?php echo 'Version: ' . str_replace('?', '', $version); ?></p>
  </div>
</div>
</div><!-- END CONTENT CONTAINER -->
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/core/parsexml.js<?php echo $version; ?>"></script>
<!--    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/core/ko.js"></script>-->
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/core/bootstrap.min.js<?php echo $version; ?>"></script>
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/core/bootbox.min.js<?php echo $version; ?>"></script>
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/core/moment.locales.js<?php echo $version; ?>"></script>  
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/core/dropzone.js<?php echo $version; ?>"></script>
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/core/lightbox.js<?php echo $version; ?>"></script>
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/addons/toastr.js<?php echo $version; ?>"></script>  
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/addons/jquery.parseParams.js<?php echo $version; ?>"></script>  
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/addons/jquery.contextMenu.js<?php echo $version; ?>"></script>  
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/addons/jquery.ui.position.js<?php echo $version; ?>"></script>  
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/addons/spin.min.js<?php echo $version; ?>"></script>  
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/app/services/config.js<?php echo $version; ?>"></script>
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/app/services/dataservice.js<?php echo $version; ?>"></script>
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/app/services/utils.js<?php echo $version; ?>"></script>
    <script type="application/javascript" src="<?php echo $this->app->relative_path; ?>Web/js/app/controllers/tabs.js<?php echo $version; ?>"></script>
    <?php echo $this->app->globalResources["js_files_html"]; ?>
  </body>
</html>
