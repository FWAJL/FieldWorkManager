<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="project_info">
<fieldset class="project_form">
  <ol class="add-new-p">
    <li style="display: none;">
      <input name="project_id" type="text" />
    </li>
    <li>
      <label><?php echo $resx["project_name"]; ?></label>
      <input name="project_name" type="text" />
    </li>
    <li>
      <label><?php echo $resx["project_num"]; ?></label>
      <input name="project_num" type="text" />
    </li>
    <li>
      <label><?php echo $resx["project_desc"]; ?></label>
      <input name="project_desc" type="text" />
    </li>
<!--    <li>
      <label><?php echo $resx["project_active_flag"]; ?></label>
      <input name="project_active_flag" type="checkbox" />
    </li>
    <li>
      <label><?php echo $resx["project_visible_flag"]; ?></label>
      <input name="project_visible_flag" type="checkbox"/>
    </li>-->
  </ol>
</fieldset>
    
</div>
