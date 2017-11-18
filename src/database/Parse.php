<?php
namespace Web\Database;

require_once PATH_ROOT_ABS."database/database.php";
require_once PATH_ROOT_ABS."database/Read.php";
require_once PATH_ROOT_ABS."database/dao/News.class.php";
require_once PATH_ROOT_ABS."database/dao/ForumPost.class.php";
require_once PATH_ROOT_ABS."database/dao/ForumSubject.class.php";
require_once PATH_ROOT_ABS."database/dao/ForumThread.class.php";
require_once PATH_ROOT_ABS."database/dao/ForumUser.class.php";
require_once PATH_ROOT_ABS."database/dao/Role.class.php";

use \Web\Database\Dao\News;
use \Web\Database\DAO\ForumPost;
use \Web\Database\DAO\ForumSubject;
use \Web\Database\DAO\ForumThread;
use \Web\Database\DAO\ForumUser;
use Web\Database\DAO\Role;

autoloadDAO();

/**
 * parse data from database to dataaccess object instances
 *
 * @author Mikael Holmbom
 * @version 1.0
 */
class Parse
{

    /**
     * filter between database entry row and dataaccessobjects\n
     * validates database value
     * @param in value read from database entry
     * @return valid string
     */
    private static function clean($in)
    {
        return htmlentities($in);
    }

    /**
     * translate database row to ForumThread
     * @param data database row
     * @return ForumThread of data
     */
    public static function toThread($data)
    {
        $thread = new ForumThread(Parse::clean($data->topic));
        $thread->setId(Parse::clean($data->id));
        $thread->setSubjectFK(Parse::clean($data->subject));

        return $thread;
    }
    /**
     * translate database row to ForumSubject
     * @param data database row
     * @return ForumSubject of data
     */
    public static function toSubject($data)
    {
        $subj = new ForumSubject();
        $subj->setId(Parse::clean($data->id));
        $subj->setTopic(Parse::clean($data->topic));
        $subj->setSubtitle(Parse::clean($data->subtitle));

        return $subj;
    }
    /**
     * translate database row to Role
     * @param data database row
     * @return Role of data
     */
    public static function toRole($data)
    {
        return new Role(
            (int) Parse::clean($data->id),
            (string) Parse::clean($data->title)
        );
    }
    /**
     * translate database row to News
     * @param data database row
     * @return News of data
     */
    public static function toNews($data)
    {
        $news = new News();
        $news->setId(Parse::clean($data->id));
        $news->setAuthorPK(Parse::clean($data->author));
        $news->setTitle(Parse::clean($data->title));
        $news->setMessage(Parse::clean($data->message));
        $news->setCreated(Parse::clean($data->created));

        return $news;
    }
    /**
     * translate database row to ForumPost
     * @param data database row
     * @return ForumPost of data
     */
    public static function toPost($data)
    {
        $post = new ForumPost();
        $post->setId(Parse::clean($data->id));
        $post->setAuthorFK(Parse::clean($data->author));
        $post->setMessage(Parse::clean($data->message));
        $post->setCreated(Parse::clean($data->created));
        $post->setEdited(Parse::clean($data->edited));
        $post->setThreadFK(Parse::clean($data->thread));

        return $post;
    }
    /**
     * translate database row to ForumUser
     * @param data object database row
     * @return ForumUser of data
     */
    public static function toUser($data)
    {
        $user = new ForumUser();
        $user->setId((int) Parse::clean($data->id));
        $user->setName(Parse::clean($data->name));
        $user->setEmail(Parse::clean(crypt(
            $data->email,
            $GLOBALS['database']['crypt_salt']
        )));
        $user->setRole(Parse::clean($data->title));
        $user->setRegistered(Parse::clean($data->registered));
        $user->setBanned($data->banned === 't' ? 1:0);

        return $user;
    }

}
