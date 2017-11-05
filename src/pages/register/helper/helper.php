<?php
namespace Web\Pages\Register\Helper;

use \Web\Database\DAO\ForumUser;
use \Web\Database\Persist;
use Web\Session\Security\Authorizer;

/**
 * if post user is valid send persistrequest to database
 */
function register()
{
    $name         = clean_input($_POST["user_name"]);
    $email        = clean_input($_POST["user_email"]);
    $passw        = clean_input($_POST["user_password"]);
    $passw_conf   = clean_input($_POST["user_password_confirm"]);

    $response = [
        'success' => false
    ];
    $validUserResponse = validUser($name, $email, $passw, $passw_conf);
    if (! $validUserResponse['success']){
        # set form input fields to previous value
        $_SESSION["input_name"] = $name;
        $_SESSION["input_email"] = $email;
        $response['message'] = $validUserResponse['message'];
        return $response;
    }

    $user = new ForumUser();
    $user->setName($name);
    $user->setEmail($email);
    $user->setRole(2); # set to user role
    $user->setBanned(False);
    $user->setRegistered(date($GLOBALS["timestamp_format"]));

    if (\Web\Database\exists($user)) {
        $response["message"] = 'user already registered';
    } else {
        $persisted = persist::forumUser($user, $passw);
        $response['success'] = $persisted;
        if ($persisted) {
            Authorizer::setAuthorizedUser($user);
            $response['message'] = 'registered user ' . $user->getName() . ' successfully';
        } else {
            $response['message'] = 'user ' . $user->getName() . ' could not be registered';
        }
    }

    return $response;
}

/**
 * cleans string\n
 * meaning translate htmlspecial chars, slashes and initial or ending whitespaces
 * @return string the clean string
 */
function clean_input(
    $data
) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlentities($data);

    return $data;
}