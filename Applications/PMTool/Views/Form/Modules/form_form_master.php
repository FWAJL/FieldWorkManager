<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>


<div id="form_info" class="data-form">
  <form action="./addMaster" class="dropzone" id="document-upload-master">
    <fieldset class="form_form form">
      <ol class="add-new-item">
        <li style="display: none;">
          <input name="form_id" type="text" />
        </li>
        <li>
          <label><?php echo $resx["form_title"]; ?></label>
          <input name="title" type="text" />
        </li>
        <div class="fallback">
          <li>
            <label><?php echo $resx["form_file"]; ?></label>
            <input name="file" type="file" multiple />
          </li>
        </div>
      </ol>
    </fieldset>
  </form>
</div>