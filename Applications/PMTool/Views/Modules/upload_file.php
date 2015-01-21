<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<form action="<?php echo $this->app->relative_path; ?>uploadFile" class="dropzone" id="document-upload">
  <div class="fallback">
    <input name="file" type="file" multiple />
  </div>
</form>
<div id="documents"></div>
