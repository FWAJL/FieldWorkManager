<?php
//Check if any confirm msg hidden has to be created    
if (isset($confirm_message) && !empty($confirm_message)) {
  foreach ($confirm_message as $key => $the_msg) {
    ?>
    <input type="hidden" id="confirmmsg-<?php echo $the_msg['confirmmsg']['operation'] ?>" value="<?php echo $the_msg['confirmmsg']['value'] ?>" />
    <?php
  }
}
?>