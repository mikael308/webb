<?php

namespace Web\Database;

require_once PATH_ROOT_ABS."database/database.php";
require_once PATH_ROOT_ABS."database/Parse.php";

/**
 * extract data from database
 * @author Mikael Holmbom
 * @version 1.0
 */
class Extract{

	static function latestThreads(
		$amount
	) {
		$latestThreads = array();

		$db_conn = connect();
		if ($db_conn){

			$res = pg_query_params(
			    $db_conn,
                " SELECT *"
                . " FROM ".$GLOBALS['database']['table']['forumthreads']." AS t"
                . " WHERE t.id IN "
                . " ("
                .  " SELECT p.thread"
                .  " FROM ".$GLOBALS['database']['table']['forumposts']." AS p"
                .  " GROUP BY p.thread"
                .  " ORDER BY MAX(p.created) DESC"
                .  " LIMIT($1)"
                . " )"
                . " ;",
                [ $amount ]
            );
			if($res){
				for($i = 0; $i < pg_num_rows($res); $i++){
					$latestThreads[] =
						Parse::toThread(pg_fetch_object(
							$res,
							$i
						));
				}
				pg_free_result($res);
			} else {
				throw new \RuntimeException(pg_last_error($db_conn));
			}

		}
		return $latestThreads;
	}
}
