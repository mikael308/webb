<?php
/**
 * defines helper functions to read scope variables 
 * 
 * @author Mikael Holmbom
 */

namespace Web\Framework\Session;

/**
 * starts session if not already active
 */
function start()
{
    if (session_status() !== PHP_SESSION_ACTIVE){
        session_start();

        if (!isset($_SESSION['settings'])) {
            $_SESSION['settings'] = new \Web\Framework\Config\Settings("../config/settings.conf");
        }
    }
}
