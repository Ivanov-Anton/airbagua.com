<?php
/**
 * @version		default.php 2 2011-11-04 - www.itfirmaet.dk
 * @package		mod_itf_call_back
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; 
 */
defined('_JEXEC') or die('Restricted access');

$showform = true;
$errormessage = "";
$buttonSubmit = JRequest::getVar('mod_itf_call_me_back_form_submit');
$recipient = $params->get('mod_itf_call_me_back_recipient');
$mod_itf_call_me_back_phonenumber_length =  $params->get('mod_itf_call_me_back_phonenumber_length');
$contact_phone	= JRequest::getVar('contact_phone');
$contact_name	= JRequest::getVar('contact_name');
$subjectandbody = $contact_name.' '.JText::_( 'MOD_ITF_CALL_ME_BACK_MAIL_TEXT' ).' : '.$contact_phone."\r\n\r\n";

if($buttonSubmit == JText::_( 'MOD_ITF_CALL_ME_BACK_SUBMIT' ))
{
	if($contact_phone != "" && $contact_phone != JText::_( 'MOD_ITF_CALL_ME_BACK_PHONE' ) && $contact_name != "" && $contact_name != JText::_( 'MOD_ITF_CALL_ME_BACK_NAME' ) && strlen($contact_phone) >= $mod_itf_call_me_back_phonenumber_length && !containsIlligalStrings($subjectandbody)) 
	{
		if($recipient == "demo@itfirmaet.dk")
		{
			$errormessage = "Message not send. The module is in demo mode";	
		}
		else
		{
			$mail = JFactory::getMailer();
			if(stripos($recipient,";") > 0){
				$recipient = explode(";", $recipient);
			}		
			$mail->addRecipient($recipient);
			$mail->setSubject($subjectandbody);
			$mail->setBody($subjectandbody);
			if ($mail->Send() === true)
			{
				$redirectUrl =  $params->get('mod_itf_call_me_back_redirect');
				if($redirectUrl != ""){
					header("Location: ".$redirectUrl, true);
				}
				else
				{
					$showform = false;
				}
			}
			else
			{
				$errormessage = "Mail could not be send. Please check your mail settings in Joomla global configuration.";	
			}
		}
	}
	else
	{
		$errormessage = "Form is not valid";	
	}
}

function containsIlligalStrings($str)
{
	$str = strtolower($str);
	if(strpos($str, "@") !== false) return true;
	if(strpos($str, "http") !== false) return true;
	if(strpos($str, "www") !== false) return true;
	if(strpos($str, "viagra") !== false) return true;
	if(strpos($str, "penis") !== false) return true;
	
	return false;
}
?>

<script type="text/javascript">
<!--
	function validateForm( frm ) {
		if(frm.contact_name.value == '') {
			alert("<?php echo JText::_( 'MOD_ITF_CALL_ME_BACK_NAME_ERROR' );?>");
			return false;
		}
		if(frm.contact_name.value == '<?php echo JText::_( 'MOD_ITF_CALL_ME_BACK_NAME' );?>') {
			alert( "<?php echo JText::_( 'MOD_ITF_CALL_ME_BACK_NAME_ERROR' );?>");
			return false;
		}
		if(frm.contact_phone.value == '<?php echo JText::_( 'MOD_ITF_CALL_ME_BACK_PHONE' );?>') {
			alert( "<?php echo JText::_( 'MOD_ITF_CALL_ME_BACK_PHONE_ERROR' );?>");
			return false;
		}
		if(frm.contact_phone.value == '') {
			alert( "<?php echo JText::_( 'MOD_ITF_CALL_ME_BACK_PHONE_ERROR' );?>");
			return false;
		}
		if(frm.contact_phone.value.length < <?php print $mod_itf_call_me_back_phonenumber_length; ?> ) {
			alert( "<?php echo JText::sprintf( 'MOD_ITF_CALL_ME_BACK_PHONE_NUMBER_LENGTH_ERROR',$mod_itf_call_me_back_phonenumber_length );?>");
			return false;
		}
		
		return true;
	}
// -->
<!--
	function doClear( formField ) {
		if (formField.value == '<?php echo JText::_( 'MOD_ITF_CALL_ME_BACK_NAME' );?>' ){
				formField.value = "";
			}
		if (formField.value == '<?php echo JText::_( 'MOD_ITF_CALL_ME_BACK_PHONE' );?>' ){
				formField.value = "";
			}
	}
// -->

</script>


<?php
	if ($showform == true){
	if($params->get('mod_itf_call_me_back_headline') != ""){
?>
    <h4 class="call_me_back_headline"><?php echo $params->get('mod_itf_call_me_back_headline');?></h4>
<?php
	}
?>
    <p class="call_me_back_pretext"><?php echo $params->get('mod_itf_call_me_back_intro');?></p>
    <form action="" method="post" class="form-validate" id="mod_itf_form" name="mod_itf_form" onsubmit="return validateForm(this);">
    <input type="hidden" name="action" value="CallMeUp" />
    <p class="call_me_back_form_name"><input onclick="doClear(this)" type="text" id="contact_name" name="contact_name" value="<?php echo JText::_( 'MOD_ITF_CALL_ME_BACK_NAME' );?>" class="inputbox input-medium" /></p>
    <p class="call_me_back_form_phone"><input type="text"  onclick="doClear(this)" id="contact_phone" name="contact_phone" value="<?php echo JText::_( 'MOD_ITF_CALL_ME_BACK_PHONE' );?>" class="inputbox input-medium" /></p>
    <p class="call_me_back_form_submit"><input type="submit" name="mod_itf_call_me_back_form_submit" value="<?php echo JText::_( 'MOD_ITF_CALL_ME_BACK_SUBMIT' );?>" class="button" /></p>
    </form>
<?php
	}else{			
?>
	<h4 class="call_me_back_headline"><?php echo $params->get('mod_itf_call_me_back_headline');?></h4>
	<p class="call_me_back_confirm_text"><?php echo $params->get('mod_itf_call_me_back_confirm');?></p>
<?php
	}
	
	if($errormessage != "")
	{
		print "<div style=\"color: red;\">".$errormessage."</div>";	
	}
	$hidelink = "";
	 if ($params->get('mod_itf_call_me_back_hide_credit')==1){
		 $hidelink = "display: none;";
	 }
	 print "<div style=\"text-align: right; padding-right: 4px; font-size: 10px;".$hidelink."\"><a title=\"". JText::_( 'MOD_ITF_CALL_ME_BACK_CREDIT_LINK_TITLE' )."\"  href=\"http://www.itfirmaet.dk\" style=\"color:#aaaaaa;\">itfirmaet.dk</a></div>";
?>

