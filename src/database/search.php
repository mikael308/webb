<?php
namespace Web\Database;

require_once "database.php";
require_once "Read.php";

/**
 * get forumusers with id as param user_id
 * @param user_id string
 * @return array list of forumusers with match
 */
function searchForumUser(
    $user_id
) {
    $db_conn = connect();
    if ($db_conn) {
        $query = "SELECT * FROM proj.get_user($1);";

        $res = pg_query_params(
            $db_conn,
            $query,
            [
                "%".$user_id."%"
            ]
        );
        if ($res) {
            $num_rows = pg_num_rows($res);
            if ($num_rows == 0) return NULL;

            $users = array();
            for ($i = 0; $i < $num_rows; $i++) {
                $data = pg_fetch_object($res, $i);
                $users[] = \Database\Read::forumUser($data->name);
            }

            pg_free_result($res);

            return $users;

        } else {
            #echo pg_last_error($db_conn);
        }
    }
    return NULL;
}

/**
 * search posts with messaging containing param post_msg
 * @param post_msg
 * @return array posts containing message as post_msg. NULL is returned if no matches were maid
 */
function searchPost($post_msg)
{
    $post_msg = htmlentities($post_msg);

    $db_conn = connect();
    if ($db_conn) {
        $query = "SELECT post.id "
            . " FROM proj.get_post($1) AS post;";

        $res = pg_query_params($db_conn, $query, array("%".$post_msg."%"));
        if($res) {
            $num_rows = pg_num_rows($res);
            if ($num_rows == 0) return NULL;

            $posts = array();
            for ($i = 0; $i < $num_rows; $i++) {
                $data = pg_fetch_object($res, $i);
                $post = \Database\Read::forumPost($data->id);

                $posts[] = $post;
            }

            pg_free_result($res);

            return $posts;

        } else {
            #echo pg_last_error($db_conn);
        }
    }
    return NULL;
}
