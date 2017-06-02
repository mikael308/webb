<?php
	/**
	 * messages used to inform the user<br>
	 * for stylesheet see css/msg.css
	 * @author Mikael Holmbom
	 * @version 1.0
	 */

	
	/**
	 * get a error message
	 * @param message the text message contained
	 * @return error message as html string
	 */
	function errorMessage($message){
		return '<p class="err_msg msg">'
			. '<strong class="msg_header">error</strong> '
			. $message
			. '</p>';
	}
	/**
	 * get a info message
	 * @param message the text message contained
	 * @return info message as html string
	 */
	function infoMessage($message){
		return '<p class="info_msg msg">'
			. '<strong class="msg_header">info</strong> '
			. $message
			. '</p>';
	}

?>