<?php
	
	/**
	 * format used for displaying timestamps
	 */
	$GLOBALS['timestamp_format']  = 'Y-m-d G:i:s';
	/**
	 * format a datetimestamp string
	 * @param datetimestamp the string to format
	 * @return transformed string 
	 */
	function formatDate($datetimestamp){
		return substr($datetimestamp, 0, 10);
	}
	/**
	 * format a datetimestamp string to date time format
	 * @param datetimestamp the string to format
	 * @return string
	 */
	function formatDateTime($datetimestamp){
		return substr($datetimestamp, 0, 19);
	}

?>