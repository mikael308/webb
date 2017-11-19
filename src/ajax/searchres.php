<?php
namespace Web\Ajax;

/**
 * use request 'value' for searchstring <br>
 * use request 'type' for searchtype<br>
 * @author Mikael Holmbom
 * @version 1.0
 */

use function Web\pagelinkForumThread;
use function Web\pagelinkPost;
use function Web\pagelinkUser;

header('Content-Type: application/json');

require_once '/vagrant/src/database/search.php';
require_once '/vagrant/src/framework/format.php';

$searchstr = $_REQUEST['value'] ?? '';
$searchType = $_REQUEST['type'] ?? '';

$suggest = [
    'status'    => 'NOFOUND'
];
$resArray = [];
if ($searchstr != '') {

    switch($searchType) {
        case 'post':
            $objArray = \Web\Database\searchPost($searchstr);
            foreach ($objArray as $obj) {
                $arr = $obj->toArray();
                $thread = $obj->getThreaD();
                $arr['label'] = \Web\Framework\Format::textToLength($obj->getMessage(), 20);
                #$arr['link'] = pagelinkForumThread($obj->getThreadFK(), $obj->getPageIndex());
                $arr['topic'] = $thread->getTopic();
                $arr['author_name'] = $obj->getAuthor()->getName();
                $resArray[] = $arr;
            }
            break;
        case 'user':
            $objArray = \Web\Database\searchForumUser($searchstr);
            foreach ($objArray as $obj) {
                $resArray[] = $obj->toArray();
            }
            break;
    }
    if (sizeof($resArray) > 0)
        $suggest['status'] = 'OK';
}

$suggest['items'] = $resArray;

$json_array = json_encode($suggest);
echo $json_array;
