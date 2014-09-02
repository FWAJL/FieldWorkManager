<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<section class="right-aside main col-lg-9 col-md-9 col-sm-9">

  <section class="project_list">
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
        <section class="col-lg-2 col-md-2">
          <!-- Button to move from active to inactive -->
          <input type="button" value="<?php echo $resx["btn_to_inactive_list"]; ?>" class="btn-primary from-active-list">

          </input>
          <!-- Button to move from active to inactive -->
          <input type="button" value="<?php echo $resx["btn_to_active_list"]; ?>"  class="btn-primary from-inactive-list">

          </input>
        </section>
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
  </section>

</section>
</div><!-- END ROW DIV -->
</div><!-- END CONTENT CONTAINER -->