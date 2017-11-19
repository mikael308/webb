<?php

namespace Web\Framework\Request;

/**
 * get index value from request GET\n
 * param must be >= 0
 * @param index string return value from GET request
 * @return
 */
function getIndex($key)
{
    $index = $_REQUEST[$key] ?? '';
    return preg_match("/^[0-9]+$/", $index)
        ? $index
        : null;
}

