<!--Promt box: genrates a general prompt box-->
<div class="modal fade prompt-modal" id="address-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <?php
  if (isset($prompt_message) && !empty($prompt_message)) {
    foreach($prompt_message as $the_msg){
      ?>
      <input type="hidden" id="promptmsg-<?php echo $the_msg['promptmsg']['operation'] ?>" value="<?php echo $the_msg['promptmsg']['value'] ?>" />
    <?php
    }
  }

  ?>
  <div class="modal-dialog modal-upload">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="prompt_title"><?php echo $resx["address_window_title"];?></h4>
      </div>

      <div class="modal-body">
        <label for="address-city"><?php echo $resx["address_window_city"];?></label>
        <input class="form-control" type="text" id="address-city" name="city">
        <label for="address-state"><?php echo $resx["address_window_state"];?></label>
        <input class="form-control" type="text" id="address-state" name="state">
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary confirmbuttons modal-update"><?php echo $resx["address_window_ok"];?></button>
        <button type="button" class="btn btn-default confirmbuttons" data-dismiss="modal"><?php echo $resx["address_window_cancel"];?></button>
      </div>

    </div>
  </div>
</div>
<!--Promt box-->