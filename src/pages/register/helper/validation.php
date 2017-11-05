<?php
namespace Web\Pages\Register\helper;

/**
 * determine if user fields is valid
 * @return array response
 */
function validUser(
    $name, 
    $email, 
    $passw, 
    $passw_conf
) {
    $response = [
        'success' => false
    ];
    $failflag = false;
    if (trim($name) == '') {
        $response['message'] = 'name cannot be empty';
        $failflag = true;
    } elseif (trim($email) == '') {
        $response['message'] = 'email cannot be empty';
        $failflag = true;
    } elseif (trim($passw) == '' || trim($passw_conf) == '') {
        $response['message'] = 'password cannot be empty';
        $failflag = true;
    } elseif (trim($passw_conf) == '') {
        $response['message'] = 'password confirmation cannot be empty';
        $failflag = true;
    }

    if (! validPassword($passw)) {
        $response['message'] = 'password not valid';
        $failflag = true;
    }
    if ($passw != $passw_conf) {
        $response['message'] ='passwords not equal';
        $failflag = true;
    }

    $response['success'] = !$failflag;
    if (!$failflag) {
        $response['message'] = 'user is valid';
    }
    return $response;
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
        validLength($password)
        && validContent($password)
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
    if (
        preg_match("/[a-z]/", $password) == 0  ||
        preg_match("/[A-Z]/", $password) == 0  ||
        preg_match("/[0-9]/", $password) == 0  ||
        preg_match("/[\\.!_\\-]/", $password) == 0
    ) {
        return False;
    }
    return True;
}
