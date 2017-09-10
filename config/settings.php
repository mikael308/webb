<?php
	/**
	 *
	 * helper functions to read from settings file\n
	 * settings.md
	 *
	 * @author Mikael Holmbom
	 * @version 1.0
	 */

	namespace Settings;

	/**
	 * read settings option value from settings file
	 * @param setting_name name of the requested settings option
	 * @return settings options value. if settings option was not found, return NULL
	 */
	function read($setting_name){
		$ret_val = NULL;
		$settings_file = fopen("../config/settings.conf", "r") or die("unable to read settings");

		while(! feof($settings_file)){
			$line = fgets($settings_file);

			$tl = trim($line);
			if(strlen($tl) == 0 || $tl[0] == "#"){
				continue;
			}

			$row_arr = explode(" ", $tl);

			if($row_arr[0] == $setting_name){
				$ret_val = $row_arr[1];

			}

		}

		fclose($settings_file);

		return $ret_val;

	}

?>
