<?php
namespace Web\Pages\Register\helper;

/**
 * determine if user fields is valid
 * @return True if valid
 */
function validUser(
    $name, 
    $email, 
    $passw, 
    $passw_conf
) {
    # validate input fields
    ##########################

    if (str_replace(" ", "", $name) == ""){
        $_SESSION["registeruser_errmsg"]
            = "name cannot be empty";
        return False;

    } elseif (str_replace(" ", "", $email) == ""){
        $_SESSION["registeruser_errmsg"]
            = "email cannot be empty";
        return False;

    } elseif (str_replace(" ", "", $passw) == ""
    || str_replace(" ", "", $passw_conf) == ""){
        $_SESSION["registeruser_errmsg"]
            = "password cannot be empty";
        return False;

    } elseif (str_replace(" ", "", $passw_conf) == ""){
        $_SESSION["registeruser_errmsg"]
            = "password confirmation cannot be empty";
        return False;
    }

    # validate password
    #####################

    if (! validPassword($passw)){
        $_SESSION["registeruser_errmsg"]
            = "password not valid";
         return False;
    }
    if ($passw != $passw_conf){
         $_SESSION["registeruser_errmsg"]
            = "passwords not equal";
         return False;
    }

    return True;
}

/**
 * determine if password is valid
 * @param password string the password to validate
 * @return True if password is valid
 */
function validPassword(
    $password
) {
    if (
        validLength($password) && 
        validContent($password)
    ) {
        return True;
    }
    return False;
}

/**
 * determine if password is of correct length\n
 * password need to be above 6 characters
 * @param password string the password to validate
 * @return True if password is valid length
 */
function validLength(
    $password
) {
    if (strlen($password) >= 6) {
        return True;
    }
    return False;
}

/**
 * validates the content of a password
 * @param password string the password to validate
 * @return True if password is valid  of content
 */
function validContent(
    $password
){
    if (#TODO skriv om....
        preg_match("/[a-z]/", $password) == 0  ||
        preg_match("/[A-Z]/", $password) == 0  ||
        preg_match("/[0-9]/", $password) == 0  ||
        preg_match("/[\\.!_\\-]/", $password) == 0
    ) {
        return False;
    }
    return True;
}
