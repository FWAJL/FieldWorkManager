<?php
if(isset($tooltip_message) && !empty($tooltip_message))
{
	//Loop and create hidden vars
	foreach($tooltip_message as $tt_key => $the_tt)
	{
		?>
        <input type="hidden" id="tooltipmsg_<?php echo $the_tt['tooltipmsg']['targetattr'] ?>" value="<?php echo $the_tt['tooltipmsg']['value'] ?>" placement="<?php echo $the_tt['tooltipmsg']['placement'] ?>" >
        <?php
	}
}
?>