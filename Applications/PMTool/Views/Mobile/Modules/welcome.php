<?php
if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
    
<div class="welcome_content_box">	
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <h2 class="welcome_tagline"><?php echo $resx["h2_welcome"]; ?> </h2>
      <div class="container mobile_view">
	     <a id="modal_trigger" href="#modal" class="btn click_button">Click here</a>
     
	     <div id="modal" class="popupContainer" style="display:none;">
		     <header class="popupHeader">
			     <span class="header_title"></span>
			     <span class="modal_close"><i class="fa fa-times"><img src="http://t0.gstatic.com/images?q=tbn:ANd9GcQjo7-YeSkQZ-7h34Mrfuzn0IuX7I7jHxyraCAszN5pkwy69e1mCg"></i></span>
		     </header>
		     
		     <section class="popupBody">
			     <!-- User Welcome Popup -->
			     <div class="social_login">
				      
				     <div class="centeredText">
					     <span>
					     <?php echo $resx["user_welcome"]; ?><br>
					     <?php echo $resx["user_message1"]; ?><br>
					     <?php echo $resx["user_message2"]; ?>
					     </span>
				     </div>
     
				     <div class="action_btns">
					      
				     </div>
			     </div>
     
			      
		     </section>
	     </div>
     </div>
</div>
</div>

