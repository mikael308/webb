<?php
/**
 * defines helper functions to read scope variables 
 * 
 * @author Mikael Holmbom
 */

namespace Session;

/**
 * starts session if not already active
 */
function start()
{
    if (session_status() !== PHP_SESSION_ACTIVE){
        session_start();
        
        #TODO read settings into session var
    }
}
