<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<section class="right-aside">
  <h2></h2>

  <section class="form_sections">
    <?php require __ROOT__ . \Library\Enums\FolderName::AppsFolderName
            . $this->app->name()
            . \Library\Enums\FolderName::ViewsFolderName
            . 'Modules/project_form.php'; ?>
    <input type="button" id="btn_add_project" value="<?php echo $resx["project_button_add"]; ?>" />
    <input type="button" id="btn_edit_project" value="<?php echo $resx["project_button_edit"]; ?>" />
    <?php require __ROOT__ . \Library\Enums\FolderName::AppsFolderName
            . $this->app->name()
            . \Library\Enums\FolderName::ViewsFolderName
            . 'Modules/facility_form.php'; ?>
    <?php require __ROOT__ . \Library\Enums\FolderName::AppsFolderName
            . $this->app->name()
            . \Library\Enums\FolderName::ViewsFolderName
            . 'Modules/company_form.php'; ?>
  </section>
</section>	
</div><!-- END CONTENT CONTAINER -->