<?php

namespace Session\Security;

require_once "authorizer.php";
use \Session\Security\Authorizer as Authorizer;

/**
 * listens for POST request for\n
 * * login\n
 * * logout
 */
function authorizationListener(){
    loginListener();
    logoutListener();
}

/**
 * listens for logout POST requests\n
 * set session authorized_user to null and redirect to index
 */
function logoutListener(){
    if (
        $_SERVER["REQUEST_METHOD"] == "POST" &&
        isset($_POST["logout"])
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
function loginListener(){
    # reset error message
    $_SESSION["login_errmsg"] = "";

    if (
        $_SERVER["REQUEST_METHOD"] == "POST" &&
        isset($_POST["login"])
    ) {
        $user   = $_POST["input_username"];
        $passw  = $_POST["input_password"];

        switch (Authorizer::login($user, $passw)){
            case 1: # successful login
                return True;
            case 0:
                $_SESSION["login_errmsg"]
                    = "could not connect to database";
                break;
            case -1:
                $_SESSION["login_errmsg"]
                    = "could not login, wrong username or password";
                break;
            case -2:
                $_SESSION["login_errmsg"]
                    = "could not login, user is banned";
                break;
            default:
                $_SESSION["login_errmsg"]
                    = "could not login";
        }

    }
    return False;
}
