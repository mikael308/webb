<?php
/**
 * helper functions used for security
 * @author Mikael Holmbom
 */

	/**
	 * cleans up a message from harmful code
	 */
	function cleanupMessage($msg){
		return htmlspecialchars($msg);
	}

?>
