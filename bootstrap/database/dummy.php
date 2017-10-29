<?php

/**
 * config script used to setup forumusers in database
 */
namespace Web\Bootstrap;
session_start();

require_once "/vagrant/src/pageref.php";

require_once PATH_ROOT_ABS."database/database.php";
require_once PATH_ROOT_ABS."database/Persist.php";
require_once PATH_ROOT_ABS."database/dao/ForumUser.class.php";
require_once PATH_ROOT_ABS."database/dao/ForumSubject.class.php";
require_once PATH_ROOT_ABS."helper/format.php";

\Web\Database\autoloadDAO();

use \Web\Database\DAO\ForumSubject;
use \Web\Database\DAO\ForumThread;
use \Web\Database\DAO\ForumUser as ForumUser;
use \Web\Database\DAO\ForumPost as ForumPost;
use \Web\Database\DAO\News;
use \Web\Database\Persist as Persist;

function outFailStats($failCount, $totalCount) {
    $successCount = $totalCount - $failCount;
    fwrite(STDOUT, "- $successCount item(s) was succesfully added");

    if ($failCount > 0) {
        fwrite(STDERR, "\n>>>Failed on $failCount item(s)");
    }
}


echo "Adding dummy: Forum users\n";
$fail = 0;
$users = [];
# password, user, email, role, banned, registered
$uservals = [
    ['abc','tom','tom@gmail.com',1,False,date($GLOBALS['timestamp_format'])],
    ['hej','tim','t@gmail.com',0,False,date($GLOBALS['timestamp_format'])],
    ['asd','mikael','mik$gmail.com',2,False,date($GLOBALS['timestamp_format'])],
    ['hhh','hilda','hilda@gmail.com',0,False,date($GLOBALS['timestamp_format'])],
    ['bbb','bernhardt','bernhardt@gmail.com',2,False,date($GLOBALS['timestamp_format'])],
    ['nikto','klaatu','klaatu@gmail.com',2,False,date($GLOBALS['timestamp_format'])],
    ['qwe','helen','helen@gmail.com',2,False,date($GLOBALS['timestamp_format'])]
];
foreach ($uservals as $user) {
    $fu = new ForumUser();
    $users[$user[1]] = $fu->setName($user[1])
        ->setEmail($user[2])
        ->setRole($user[3])
        ->setBanned($user[4])
        ->setRegistered($user[5]);
    try {
        Persist::forumUser($fu, $user[0]);
    } catch (\Exception $e) {
        $fail++;
        echo "failed on user [$user[1]]\n";
        echo $e->getMessage()."\n";
    }
}
outFailStats($fail, sizeof($users));


echo "\nAdding dummy: Forum subjects\n";
$fail = 0;
$subjects = [];
#topic, subtitle
$subjectsval = [
    ['news', 'not old'],
    ['general', 'average stuff'],
    ['other', 'not like any other, just ordinary other']
];
foreach ($subjectsval as $subject) {
    try {
        $s = new ForumSubject();
        $subjects[] = $s->setTopic($subject[0])
            ->setSubtitle($subject[1]);
        Persist::forumSubject($s);
    } catch (\Exception $e) {
        $fail++;
        echo $e->getMessage()."\n";
    }
}
outFailStats($fail, sizeof($subjects));


echo "\nAdding dummy: Forum threads\n";
$fail = 0;
$threads = [];
#topic, subjectFK
$threadsval = [
    ['kokbok',$subjects[0]->getPrimaryKey()],
    ['destroy the world', $subjects[1]->getPrimaryKey()],
    ['pigeon', $subjects[1]->getPrimaryKey()],
    ['seagull', $subjects[1]->getPrimaryKey()]
];
foreach ($threadsval as $thread) {
    try {
        $t = new ForumThread($thread[0]);
        $threads[] = $t->setSubjectFK($thread[1]);
        Persist::forumThread($t);
    } catch (\Exception $e) {
        $fail++;
        echo $e->getMessage()."\n";
    }
}
outFailStats($fail, sizeof($threads));


echo "\nAdding dummy: Forum posts\n";
$fail = 0;
$posts = [];
# threadFK, authorFK, message
$postvals = [
    [$threads[0]->getPrimaryKey(), $users['tom']->getPrimaryKey(),'bird is the word!'],
    [$threads[1]->getPrimaryKey(), $users['helen']->getPrimaryKey(),'I - I thought you were...'],
    [$threads[1]->getPrimaryKey(), $users['klaatu']->getPrimaryKey(),'I was.'],
    [$threads[1]->getPrimaryKey(), $users['helen']->getPrimaryKey(),'You mean... he has the power of life and death?'],
    [$threads[1]->getPrimaryKey(), $users['klaatu']->getPrimaryKey(),'No. That power is reserved to the Almighty Spirit. This technique, in some cases, can restore life for a limited period.'],
    [$threads[1]->getPrimaryKey(), $users['helen']->getPrimaryKey(),'But... how long?'],
    [$threads[1]->getPrimaryKey(), $users['klaatu']->getPrimaryKey(),'You mean how long will I live? That no one can tell. '],
    [$threads[3]->getPrimaryKey(), $users['tom']->getPrimaryKey(),'klaatu verada'],
    [$threads[3]->getPrimaryKey(), $users['hilda']->getPrimaryKey(),'nikto'],
    [$threads[3]->getPrimaryKey(), $users['hilda']->getPrimaryKey(),'Lorem ipsum dolor sit amet, vel id tollit audiam sanctus, no quem reque accusamus qui. Sonet copiosae senserit usu an. Eu vix antiopam accusamus consulatu, an volumus perpetua eam. Ut alii nostro mea, sit movet congue tantas ut.
Cum in antiopam conceptam suscipiantur, vim ad augue vitae, an clita oblique mea. Labores graecis ex mei. Sit ea quod omnis consequat, cum in maiorum sadipscing. Id scribentur delicatissimi sit, ei vis movet timeam. Qui doming vulputate at, ad ornatus oporteat sea, vix liber dictas splendide et. No tollit malorum quaestio nam.
Te esse dicta definitionem his, atqui eripuit albucius pri eu, est ut alii consulatu. Solum utinam te usu, prima eloquentiam no mei. Id nec quando vocent moderatius, cu mei primis animal reprimique. In pro facer dissentiunt, reque disputando id eum. Congue quodsi omittantur usu ad, vim dolore ancillae et, per noster aliquip fastidii ea. Graeco option ex mel, mucius oportere his te, nam probo iisque voluptua no.'],
    [$threads[2]->getPrimaryKey(), $users['mikael']->getPrimaryKey(),'kandar'],
];
$alpha = 'abcdefghijklmnopqrstvwxyz';
foreach (str_split($alpha) as $char) {
    $postvals[] = [$threads[2]->getPrimaryKey(),$users['hilda']->getPrimaryKey(),$char];
}
foreach ($postvals as $post) {
    try {
        $p = new ForumPost();
        $posts[] = $p->setThreadFK($post[0])
            ->setAuthorFK($post[1])
            ->setMessage($post[2]);
        Persist::forumPost($p);
    } catch (\Exception $e) {
        $fail++;
        echo "Error on post with message:\n$post[2]\n";
        echo $e->getMessage();
    }
}
outFailStats($fail, sizeof($posts));


echo "\nAdding dummy: Forum news\n";
$fail = 0;
$news = [];
$newsval = [
    ['init header', $users['tom']->getPrimaryKey(), 'init the forum'],
    ['update', $users['mikael']->getPrimaryKey(), 'update the forum']
];
foreach ($newsval as $news) {
    try {
        $n = new News();

        $news[] = $n->setTitle($news[0])
            ->setAuthorPK($news[1])
            ->setMessage($news[2]);
        Persist::news($n);
    } catch(\Exception $e) {
        $fail++;
        echo "Failed to persist news with topic \n$news[0]\n";
        echo $e->getMessage()."\n";
    }
}
outFailStats($fail, sizeof($news));


echo "\n\n- dummy values finished\n";
