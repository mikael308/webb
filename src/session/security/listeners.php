<?php

namespace Web\Session\Security;

require_once PATH_ROOT_ABS."session/security/authorizer.php";
use \Web\Session\Security\Authorizer as Authorizer;

/**
 * listens for POST request for\n
 * * login\n
 * * logout
 */
function authorizationListener()
{
    loginListener();
    logoutListener();
}

/**
 * listens for logout POST requests\n
 * set session authorized_user to null and redirect to index
 */
function logoutListener()
{
    if (
        $_SERVER['REQUEST_METHOD'] == 'POST' &&
        isset($_POST['logout'])
    ) {
        Authorizer::logout();

        header("Location: " . $GLOBALS["pagelink"]["index"]);
        exit();
    }
}

/**
 * listens for login POST request\n
 * sets session authorized_user to logged in user
 * @return True if login was successful
 */
function loginListener()
{
    # reset error message
    $_SESSION['login_errmsg'] = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
        $user   = $_POST['input_username'];
        $passw  = $_POST['input_password'];

        $response = Authorizer::login($user, $passw);
        $_SESSION['login_errmsg'] = $response['message'];

        return $response['success'];
    }
    return False;
}
