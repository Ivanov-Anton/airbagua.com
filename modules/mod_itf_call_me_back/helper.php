<?php
/**
 * @version		helper.php 2 2011-11-04 - www.itfirmaet.dk
 * @package		mod_itf_call_back
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; 
 */
	class modItfCallMeBackHelper
	{
	    function SendCallMeBack( $recipient,$subject, $body )
	    {
				$mail = JFactory::getMailer();
				
				if(stripos($recipient,";") > 0){
					$recipient = explode(";", $recipient);
				}
				
				if($recipient == "demo@itfirmaet.dk")
				{
					return true;	
				}

				$mail->addRecipient($recipient);
				$mail->setSubject($subject);
				$mail->setBody($body);
				$sent = $mail->Send();
				return $sent;
	    }
	}
?>