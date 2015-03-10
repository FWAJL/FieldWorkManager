<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <div class="project_list">
    <!--          <h3>  -->
    <h3></h3>
    <div class="content-container container-fluid">
      <div class="row">
        <?php
        require $popup_msg_module;
		require $prompt_msg_module;
        ?>
        <input type="hidden" name="redirectOnSuccess" id="redirectOnSuccess" value="<?php echo $redirect_on_success ?>">
      </div>
    </div>
  </div>
</div><!-- END RIGHT ASIDE MAIN -->