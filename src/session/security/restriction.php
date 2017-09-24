<?php
/**
 * defines listeners for authorization
 *
 * @author Mikael Holmbom
 * @version 1.0
 */

require_once "./database/database.php";
require_once "./database/Read.php";
require_once "authorizer.php";

use Web\Session\Security\Authorizer as Authorizer;

/**
 * if session authorized_user is not authorized:\n
 * redirect to param page
 * @param redirectPage string the page url to redirect client to
 */
function restrictedToAuthorized(
    $redirectPage = null
) {
    if ($redirectPage == null)
        $redirectPage = $GLOBALS["pagelink"]["register"];
    if (!Authorizer::userIsAuthorized()) {
        header("Location: " . $redirectPage);
        exit();
    }
}

/**
 * if session authorized_user is not admin:\n
 * redirect to param page
 * @param redirectPage the page to redirect client to
 */
function restrictedToAdmin(
    $redirectPage
) {
    restrictedToAuthorized($redirectPage);
    $authUser = Authorizer::getAuthorizedUser();
    if (
        $authUser == null ||
        ! $authUser->isAdmin()
    ) {
        header("Location: " . $redirectPage);
        exit();
    }
}
