<?php
if(isset($tooltip_message) && !empty($tooltip_message))
{
	//Loop and create hidden vars
	foreach($tooltip_message as $tt_key => $the_tt)
	{
		//Check existence of other attributes like delay, create hidden attributes accordingly
		$delay_str = '';
		if(isset($the_tt['tooltipmsg']['delayshow'])) {
			$delay_str = ' delayshow="' . $the_tt['tooltipmsg']['delayshow'] . '" delayhide="' . $the_tt['tooltipmsg']['delayhide'] . '"';
		}
		?>
    <input type="hidden" id="tooltipmsg_<?php echo $the_tt['tooltipmsg']['targetattr'] ?>" value="<?php echo $the_tt['tooltipmsg']['value'] ?>" placement="<?php echo $the_tt['tooltipmsg']['placement'] ?>"<?php echo $delay_str ?> >
    <?php
	}
}
?>