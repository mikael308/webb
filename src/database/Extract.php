<?php

namespace Web\Database;

require_once "database.php";
require_once "Parse.php";
#use Database\Parse;
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

			$query = " SELECT *"
				. " FROM ".$GLOBALS['database']['table']['forumthreads']." AS t"
				. " WHERE t.id IN "
				. " ("
				.  " SELECT p.thread"
				.  " FROM ".$GLOBALS['database']['table']['forumposts']." AS p"
				.  " GROUP BY p.thread"
				.  " ORDER BY MAX(p.created) DESC"
				.  " LIMIT($amount)"
				. " )"
				. " ;";

			$res = pg_query($db_conn, $query);
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
