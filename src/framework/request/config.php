<?php

namespace Web\Framework\Request;

use Web\Framework\Config\Settings;

function getSettings() : Settings
{
    return $_SESSION['settings'] ?? null;
}

