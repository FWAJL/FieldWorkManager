<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div id="client_info"  class="data-form">
  <fieldset class="client_form">
    <ol class="add-new-p">
      <li>
        <label><?php echo $resx["client_name"]; ?></label>
        <input name="client_name" type="text" />
      </li>
      <li>
        <label><?php echo $resx["client_contact_name"]; ?></label>
        <input name="client_contact_name" type="text" />
      </li>
      <li>
        <label><?php echo $resx["client_contact_phone"]; ?></label>
        <input name="client_contact_phone" type="text" />
      </li>
      <li>
        <label><?php echo $resx["client_contact_email"]; ?></label>
        <input name="client_contact_email" type="text" />
      </li>
    </ol>
  </fieldset>

</div>
