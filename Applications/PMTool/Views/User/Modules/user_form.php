<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="user_info"  class="user_edit data-form">
  <fieldset class="user_form">
    <ol class="add-new-item">
      <li style="display: none;">
        <input name="user_id" type="text" />
      </li>
      <li>
        <select name="user_type" id="user-type">
          <option value=""><?php echo $resx['user_type_option']; ?></option>
          <?php foreach($user_types as $userType): ?>
            <option value="<?php echo $userType; ?>"><?php echo $userType; ?></option>
          <?php endforeach; ?>
        </select>
      </li>
      <li>
        <label><?php echo $resx["user_login"]; ?></label>
        <input name="user_login" type="text" />
      </li>
      <li>
        <label><?php echo $resx["user_password"]; ?></label>
        <input name="user_password" type="text" placeholder="<?php echo $resx["user_password_tip"]; ?>" />
      </li>
      <li>
        <label><?php echo $resx["user_hint"]; ?></label>
        <input name="user_hint" type="text" />
      </li>
    </ol>
  </fieldset>

</div>
