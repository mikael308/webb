<?php
namespace Web\Database;

require_once PATH_ROOT_ABS."database/database.php";
require_once PATH_ROOT_ABS."database/Read.php";

/**
 * get forumusers with id as param user_id
 * @param user_id string
 * @return array|null list of forumusers with match
 */
function searchForumUser(
    $user_id
) {
    $users = [];
    $db_conn = connect();
    if ($db_conn) {

        $res = pg_query_params(
            $db_conn,
            "SELECT * FROM proj.get_user($1);",
            [
                "%".$user_id."%"
            ]
        );
        if ($res) {
            $num_rows = pg_num_rows($res);
            for ($i = 0; $i < $num_rows; $i++) {
                $data = pg_fetch_object($res, $i);
                $users[] = \Web\Database\Read::forumUserByName($data->name);
            }

            pg_free_result($res);

        } else {
            #echo pg_last_error($db_conn);
        }
    }
    return $users;
}

/**
 * search posts with messaging containing param post_msg
 * @param post_msg
 * @return array posts containing message as post_msg. NULL is returned if no matches were maid
 */
function searchPost($post_msg)
{
    $post_msg = htmlentities($post_msg);
    $posts = [];

    $db_conn = connect();
    if ($db_conn) {

        $res = pg_query_params(
            $db_conn,
            "SELECT post.id FROM proj.get_post($1) AS post;",
            [
                "%$post_msg%"
            ]
        );

        if ($res) {
            $n_rows = pg_num_rows($res);
            for ($i = 0; $i < $n_rows; $i++) {
                $data = pg_fetch_object($res, $i);
                $post = \Web\Database\Read::forumPost($data->id);

                $posts[] = $post;
            }

            pg_free_result($res);

        } else {
            #echo pg_last_error($db_conn);
        }
    }
    return $posts;
}
