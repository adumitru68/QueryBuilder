<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 5/27/2017 12:38 PM
 */

return [

	'replicationEnable' => false,

	'slave_statements' => [ 'SELECT' ],

	/**
	 * if not Replication support use only first entry in master_data_connect
	 */
	'master_data_connect' => [
		[
			'host' => 'localhost',
			'user' => 'root',
			'password' => '',
			'dbname' => 'vertex_physio_app'
		]
	],

	/**
	 * Use if replicationEnable is true. Config one or multiple slave connection
	 */
	'slave_data_connect' => [
		[
			'host' => 'localhost',
			'user' => 'guser',
			'password' => '1234',
			'dbname' => 'classicmodels'
		],
		[
			'host' => 'localhost',
			'user' => 'guser',
			'password' => '1234',
			'dbname' => 'classicmodels'
		]
	],

	'db_log' => [
		'enable_log_errors' => false,
		'enable_log_query_duration' => false,
		'log_path_errors' => $_SERVER[ 'DOCUMENT_ROOT' ] . '/tmp/db_errors/',
		'log_path_query_duration' => $_SERVER[ 'DOCUMENT_ROOT' ] . '/tmp/db_query_duration/',
	],

	'use_table_prefix' => false,
	'table_prefix' => 'prefix_',

];