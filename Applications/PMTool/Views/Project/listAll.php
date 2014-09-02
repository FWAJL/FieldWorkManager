<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-9 col-md-9 col-sm-9">

  <div class="project_list">
    <h3><?php echo $resx["project_list_all_header"]; ?></h3>
    <div class="content-container container-fluid">
      <div class="row">
        <h4 class="col-lg-5 col-md-5"><?php echo $resx["h3_projects_active"]; ?></h4>
        <span class="col-lg-2 col-md-2"></span>
        <h4 class="col-lg-5 col-md-5"><?php echo $resx["h3_projects_inactive"]; ?></h4>
      </div>
      <div class="row">
        <ol id="active-list" class="list-panel col-lg-5 col-md-5">
          <?php
          foreach ($projects as $project) {
            if ($project->active) {
              echo "<li data-project-id=\"" . $project->project_id . "\" class=\"select_item ui-widget-content\">"
              . $project->project_name
              . "</li>";
            }
          }
          ?>              
        </ol>
        <div class="col-lg-2 col-md-2">
          <div class="buttons">
            <input type="button" value="<?php echo $resx["btn_to_inactive_list"]; ?>" class="btn btn-warning from-active-list" />
            <input type="button" value="<?php echo $resx["btn_to_active_list"]; ?>"  class="btn btn-warning from-inactive-list" />            
          </div>
        </div>
        <ol id="inactive-list" class="list-panel col-lg-5 col-md-5">
          <?php
          foreach ($projects as $project) {
            if (!$project->active) {
              echo "<li data-project-id=\"" . $project->project_id . "\" class=\"select_item ui-widget-content\">"
              . $project->project_name
              . "</li>";
            }
          }
          ?>              
        </ol>
      </div>
    </div>
  </div>

</div>
</div><!-- END ROW DIV -->
</div><!-- END CONTENT CONTAINER -->