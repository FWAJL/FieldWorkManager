<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<form action="<?php echo $this->app->relative_path; ?>file/upload" class="dropzone" id="document-upload">
  <fieldset class="form">
    <ol class="add-new-item">
      <li style="display: none;">
        <input type="hidden" name="itemCategory"/>
        <input type="hidden" name="itemId"/>
        <input type="hidden" name="itemReplace" value="false"/>
      </li>
      <li>
        <label><?php echo $resx["form_title"]; ?></label>
        <input type="text" name="title"/>
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
