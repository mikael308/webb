<?php
/**
 *
 * helper class to read from settings file\n
 * settings.md
 *
 * @author Mikael Holmbom
 * @version 1.0
 */

namespace Session\Config;

class Settings
{
    protected $settings_filename;

    protected $settingsValues;

    public function __construct($settingsFileName)
    {
        $this->settings_filename = $settingsFileName;
        $this->reset();
	}

	public function reset()
    {
        $this->settingsValues = [];

        $settings_file = fopen($this->settings_filename, "r") or die("unable to read settings");

        while (! feof($settings_file)) {
            $line = fgets($settings_file);

            $tl = trim($line);
            if(strlen($tl) == 0 || $tl[0] == "#"){
                continue;
            }

            $row_arr = explode(" ", $tl);

            $this->settingsValues["<?= $row_arr[0] ?>"] = $row_arr[1];

        }

        fclose($settings_file);
    }

    public function value(
        $settings_key
    ) {
	    return $this->settingsValues["$settings_key"];
    }

    /**
     * read settings option value from settings file
     * @param setting_name string name of the requested settings option
     * @return string settings options value. if settings option was not found, return NULL
     */
    public function read($setting_name){
        $ret_val = NULL;
        $settings_file = fopen($this->settings_filename, "r") or die("unable to read settings");

        while (! feof($settings_file)) {
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

}
