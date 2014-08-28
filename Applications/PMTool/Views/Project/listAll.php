<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<section class="right-aside main col-lg-9 col-md-9 col-sm-9">

  <section class="project_list">
    <h3><?php echo $resx["project_list_all_header"]; ?></h3>
    <div class="content-container container-fluid">
      <div class="row">
        <section class="col-lg-5 col-md-5">
          <h4><?php echo $resx["h3_projects_active"]; ?></h4>
          <section class="list-panel">
            <ul id="active-list" class="connectedSortable">
            <?php
            foreach ($projects as $project) {
              if ($project->active) {
                echo "<li data-projet-name=\"" . $project->project_name
                . "\" data-project-id=\"" . $project->project_id . "\" class=\"select_item ui-widget-content\">"
                . $project->project_name . "</li>";
              }
            }
            ?>              
            </ul>
          </section>
        </section>
        <section class="col-lg-2 col-md-2">
          <!-- Button to move from active to inactive -->
          <!--<button type="button" value=""/>-->
          <!-- Button to move from active to inactive -->
          <!--<button type="button" value=""/>-->
        </section>
        <section class="col-lg-5 col-md-5">
          <h4><?php echo $resx["h3_projects_inactive"]; ?></h4>
          <section class="list-panel">
            <ul id="inactive-list" class="connectedSortable">
            <?php
            foreach ($projects as $project) {
              if (!$project->active) {
                echo "<li data-projet-name=\"" . $project->project_name
                . "\" data-project-id=\"" . $project->project_id . "\" class=\"select_item ui-widget-content\">"
                . $project->project_name . "</li>";
              }
            }
            ?>              
            </ul>
          </section>
        </section>
      </div>
    </div>
  </section>

</section>
</div><!-- END ROW DIV -->
</div><!-- END CONTENT CONTAINER -->