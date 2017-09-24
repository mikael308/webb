<?php

namespace Web\Helper;
/**
 * format used for displaying timestamps
 */
$GLOBALS["timestamp_format"]  = "Y-m-d G:i:s";

class Format
{

    /**
     * format a datetimestamp string
     * @param datetimestamp string string to format
     * @return string transformed string
     */
    public static function date($datetimestamp)
    {
        return substr($datetimestamp, 0, 10);
    }
    /**
     * format a datetimestamp string to date time format
     * @param datetimestamp string string to format
     * @return string
     */
    public static function dateTime($datetimestamp)
    {
        return substr($datetimestamp, 0, 19);
    }

    /**
     * format string to a maxlength
     * @param text string text to format
     * @param max int max string length
     * @return string formatted
     */
    public static function textToLength(
        $text,
        $max
    ) {
        if (strlen($text) <= $max)
            return $text;
        return substr($text, 0, $max) . "...";
    }

    /**
     * @param $txt
     * @return string
     */
    public static function initialUpper(
        $txt
    ) {
        $l = strlen($txt);
        if ($l < 1) { return ""; }

        $initialUpperString = strtoupper(substr($txt, 0, 1));
        if ($l == 1) {
            return $initialUpperString . strtolower(substr($txt, 1));
        }

        return $initialUpperString;
    }

    public static function pathToNS($path)
    {
        return str_replace("/", "\\", $path);
    }

    public static function nsToPath(
        $path
    ) {
        return str_replace('\\', '/', $path);
    }

}


