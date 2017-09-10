<?php

namespace Session\Security;

require_once "./database/database.php";
require_once "./database/dao/ForumUser.class.php";

class Authorizer {

    public static function setAuthorizedUser(
        \Database\DAO\ForumUser $user
    ) {
        $_SESSION['AUTHUSER'] = $user;
    }

    public static function getAuthorizedUser()
    {
        return isset($_SESSION['AUTHUSER']) 
            ? $_SESSION['AUTHUSER']
            : null;
    }

    public static function userIsAuthorized()
    {
        return Authorizer::getAuthorizedUser() != null;
    }

    public function logout()
    {
        $_SESSION['AUTHUSER'] = null;
    }

    /**
     * make a login request to database
     * @return 1: login was successful\n0: no contact with database\n-1: failed password/username match\n-2: user banned
     */
    function login(
        $userPK,
        $password
    ) {
        $retVal = 0;

        $db_conn = \Database\connect();
        if ($db_conn) {

            $query = "SELECT fuser.password, fuser.name "
                . " FROM " . $GLOBALS['dbtable_forumusers'] . " AS fuser "
                . " WHERE name= $1;";

            $res = pg_query_params(
                $db_conn,
                $query,
                array($userPK)
            );
            if ($res) {
                # query OK

                # found 1 matching result
                if (pg_num_rows($res) == 1) { 
                    $data = pg_fetch_object($res, 0);
                    
                    $crypt_inpassw = crypt(
                        $password,
                        $GLOBALS['crypt_salt']
                    );

                    if (
                        strcmp($data->password, $crypt_inpassw) == 0
                    ) {
                        #if(hash_equals($data->password, crypt(password, $data->password))){ # successful login
                        $user = \Database\Read::forumUser($data->name);

                        if ($user->isBanned()) {
                            $retVal = -2;
                        } else {
                            # successful login
                            $retVal = 1;
                            Authorizer::setAuthorizedUser($user);
                        }
                    } else {
                        # password and username not matching
                        $retVal = -1;
                    }

                } else {
                    # found less or more than 0 rows
                    $retVal = -1;
                }
                pg_free_result($res);
          
            } else {
                # query !OK
                $retVal = -1;
            }

        } else {
            # database connection failed
        }

      return $retVal;
    }

}
