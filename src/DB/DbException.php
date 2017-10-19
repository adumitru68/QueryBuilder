<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 5/27/2017 7:46 PM
 */

namespace Qpdb\QueryBuilder\DB;


class DbException extends \Exception
{

	const DB_CONNECTION_ERROR = 1;
	const DB_QUERY_ERROR = 2;
	const DB_ERROR_MASTER_DATA_CONNECTION_MISSING = 3;


}