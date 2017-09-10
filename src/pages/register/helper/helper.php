<?php

/**
 * if post user is valid send persistrequest to database
 */
function register()
{
    $name         = clean_input($_POST["user_name"]);
    $email        = clean_input($_POST["user_email"]);
    $passw        = clean_input($_POST["user_password"]);
    $passw_conf   = clean_input($_POST["user_password_confirm"]);

    if (! validUser($name, $email, $passw, $passw_conf)){
        # set form input fields to previous value
        $_SESSION["input_name"] = $name;
        $_SESSION["input_email"] = $email;
        return;
    }

    $user = new ForumUser();
    $user->setName($name);
    $user->setEmail($email);
    $user->setRole(2); # set to user role
    $user->setBanned(False);
    $user->setRegistered(date($GLOBALS["timestamp_format"]));

    if (exists($user)){
        $_SESSION["registeruser_errmsg"] = "email already registered";
        return;
    }

    if (persist::forumUser($user, $passw)){
        # user is successfully registered in database
        $user = read::forumUser($user->getPrimaryKey());
        $_SESSION["authorized_user"] = $user;
        
        header("Location: " 
            . pagelinkUser($user->getPrimaryKey()));
    }
}

/**
 * cleans string\n
 * meaning translate htmlspecial chars, slashes and initial or ending whitespaces
 * @return the clean string
 */
function clean_input(
    $data
) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlentities($data);

    return $data;
}