<?php

namespace Web\Session\Security;

require_once PATH_ROOT_ABS."database/database.php";
require_once PATH_ROOT_ABS."database/Read.php";
require_once PATH_ROOT_ABS."database/dao/ForumUser.class.php";

class Authorizer {

    /**
     * sets the authorized user
     * @param \Web\Database\DAO\ForumUser $user
     */
    public static function setAuthorizedUser(
        \Web\Database\DAO\ForumUser $user
    ) {
        $_SESSION['authorized_user_id'] = $user->getPrimaryKey();
    }

    /**
     * get the id of current authorized user
     * @return string|null
     */
    public static function getAuthorizedUserId()
    {
        return $_SESSION['authorized_user_id'] ?? null;
    }
    /**
     * gets the current authorized user
     * @return null|\Web\Database\DAO\ForumUser
     */
    public static function getAuthorizedUser()
    {
        $userId = Authorizer::getAuthorizedUserId();
        if ($userId != null) {
            return \Web\Database\Read::forumUserById($userId);
        }
        return null;
    }

    /**
     * determine if user is authorized
     * @return bool true if user is authorized
     */
    public static function userIsAuthorized()
    {
        return Authorizer::getAuthorizedUser() != null;
    }

    /**
     * logs out current user.
     * sets the authoriezd user id to null
     */
    public static function logout()
    {
        $_SESSION['authorized_user_id'] = null;
    }

    /**
     * make a login request to database
     * @param $userName
     * @param $password
     * @return int 1: login was successful\n0: no contact with database\n-1: failed password/username match\n-2: user banned
 */
    public static function login(
        $userName,
        $password
    ) {
        $responseMessages = [
            -2      => 'could not login, user is banned',
            -1      => 'could not login, wrong username or password',
            0       => 'could not connect to database',
            1       => 'successful login'
        ];

        $response = [
            'success' => false
        ];

        $db_conn = \Web\Database\connect();
        if ($db_conn) {

            $query = "SELECT fuser.id, fuser.name, fuser.password "
                . " FROM " . $GLOBALS['database']['table']['forumusers'] . " AS fuser "
                . " WHERE fuser.name=$1;";

            $res = pg_query_params(
                $db_conn,
                $query,
                [ $userName ]
            );

            if ($res) {
                # query OK

                # found 1 matching result
                if (pg_num_rows($res) == 1) { 
                    $data = pg_fetch_object($res, 0);
                    
                    $crypt_inpassw = crypt($password, $GLOBALS['database']['crypt_salt']);

                    if (strcmp($data->password, $crypt_inpassw) == 0) {
                        #if(hash_equals($data->password, crypt(password, $data->password))){ # successful login
                        $user = \Web\Database\Read::forumUserById($data->id);

                        if ($user->isBanned()) {
                            $response['code'] = -2;
                        } else {
                            # successful login
                            $response['code'] = 1;
                            $response['success'] = true;
                            Authorizer::setAuthorizedUser($user);
                        }
                    } else {
                        # password and username not matching
                        $response['code'] = -1;
                    }

                } else {
                    # found less or more than 0 rows
                    $response['code'] = -1;
                }
                pg_free_result($res);
          
            } else {
                # query !OK
                $response['code'] = -1;
            }

        } else {
            # database connection failed
            $response['code'] = 0;
        }
        $response['message'] = $responseMessages[$response['code']];
        return $response;
    }

}
