<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="user_info"  class="user_edit data-form">
  <fieldset class="user_form">
    <ol class="add-new-item">
      <li style="display: none;">
        <input name="user_id" type="text" />
      </li>
      <li>
        <label><?php echo $resx["user_password"]; ?></label>
        <input name="user_password" type="password" />
      </li>
      <li>
        <label><?php echo $resx["user_hint"]; ?></label>
        <input name="user_hint" type="text" />
      </li>
    </ol>
  </fieldset>

</div>
