<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 6/1/2017 4:14 PM
 */

namespace Qpdb\QueryBuilder\DB;


class DbService
{

	const QUERY_TYPE_INSERT = 'INSERT';
	const QUERY_TYPE_DELETE = 'DELETE';
	const QUERY_TYPE_UPDATE = 'UPDATE';
	const QUERY_TYPE_SELECT = 'SELECT';
	const QUERY_TYPE_REPLACE = 'REPLACE';
	const QUERY_TYPE_SHOW = 'SHOW';
	const QUERY_TYPE_DESC = 'DESC';
	const QUERY_TYPE_EMPTY = 'EMPTY';
	const QUERY_TYPE_OTHER = 'OTHER';
	const QUERY_TYPE_EXPLAIN = 'EXPLAIN';

	const ON_ERROR_THROW_EXCEPTION = 1;
	const ON_ERROR_RETURN_ERROR = 2;

	/**
	 * @var DbService
	 */
	private static $instance;

	/**
	 * @var \PDO
	 */
	private $pdo;

	/**
	 * @var \PDOStatement
	 */
	private $sQuery;

	/**
	 * @var array
	 */
	private $parameters = [];


	/**
	 * @param string $query
	 * @param array $params
	 * @param int $fetchMode
	 * @return array|int|null
	 */
	public function query( $query, $params = null, $fetchMode = \PDO::FETCH_ASSOC )
	{

		$query = trim( str_replace( "\r", " ", $query ) );
		$statement = self::getQueryStatement( $query );

		$this->queryInit( $query, $params );

		if( $statement === self::QUERY_TYPE_SELECT ||
			$statement === self::QUERY_TYPE_SHOW ||
			$statement === self::QUERY_TYPE_DESC ||
			$statement === self::QUERY_TYPE_EXPLAIN
		) {
			return $this->sQuery->fetchAll( $fetchMode );
		}
		elseif( $statement === self::QUERY_TYPE_INSERT ||
			$statement === self::QUERY_TYPE_UPDATE ||
			$statement === self::QUERY_TYPE_DELETE
		) {
			return $this->sQuery->rowCount();
		}
		else {

			return NULL;
		}
	}

	/**
	 * @param $query
	 * @param array $params
	 * @return array|null
	 */
	public function column( $query, $params = null )
	{
		$this->queryInit( $query, $params );

		$query = trim( str_replace( "\r", " ", $query ) );
		$statement = self::getQueryStatement( $query );

		if( $statement === self::QUERY_TYPE_EXPLAIN )
			return $this->sQuery->fetchAll( \PDO::FETCH_ASSOC );

		$Columns = $this->sQuery->fetchAll( \PDO::FETCH_NUM );

		$column = null;

		foreach( $Columns as $cells ) {
			$column[] = $cells[ 0 ];
		}

		return $column;
	}

	public function row( $query, $params = null, $fetchmode = \PDO::FETCH_ASSOC )
	{
		$this->queryInit( $query, $params );

		$query = trim( str_replace( "\r", " ", $query ) );
		$statement = self::getQueryStatement( $query );

		if( $statement === self::QUERY_TYPE_EXPLAIN )
			return $this->sQuery->fetchAll( \PDO::FETCH_ASSOC );

		$result = $this->sQuery->fetch( $fetchmode );
		$this->sQuery->closeCursor(); // Frees up the connection to the server so that other SQL statements may be issued,

		return $result;
	}

	public function single( $query, $params = null )
	{
		$this->queryInit( $query, $params );
		$result = $this->sQuery->fetchColumn();
		$this->sQuery->closeCursor(); // Frees up the connection to the server so that other SQL statements may be issued

		return $result;
	}


	/**
	 * @param string $query
	 * @param array $parameters
	 */
	private function queryInit( $query, $parameters = [] )
	{
		$this->pdo = DbConnect::getInstance()->getConnection( self::getQueryStatement( $query ) );
		$startQueryTime = microtime( true );

		try {

			/**
			 * Prepare query
			 */
			$this->sQuery = $this->pdo->prepare( $query );

			/**
			 * Add parameters to the parameter array
			 */
			if( self::isArrayAssoc( $parameters ) )
				$this->bindMore( $parameters );
			else
				foreach( $parameters as $key => $val )
					$this->parameters[] = array( $key + 1, $val );

			if( count( $this->parameters ) ) {
				foreach( $this->parameters as $param => $value ) {
					if( is_int( $value[ 1 ] ) ) {
						$type = \PDO::PARAM_INT;
					}
					elseif( is_bool( $value[ 1 ] ) ) {
						$type = \PDO::PARAM_BOOL;
					}
					elseif( is_null( $value[ 1 ] ) ) {
						$type = \PDO::PARAM_NULL;
					}
					else {
						$type = \PDO::PARAM_STR;
					}
					$this->sQuery->bindValue( $value[ 0 ], $value[ 1 ], $type );
				}
			}

			$this->sQuery->execute();

			if( DbConfig::getInstance()->isEnableLogQueryDuration() ) {
				$duration = microtime( true ) - $startQueryTime;
				DbLog::getInstance()->writeQueryDuration( $query, $duration );
			}

		} catch( \PDOException $e ) {
			if( DbConfig::getInstance()->isEnableLogErrors() ) {
				DbLog::getInstance()->writeQueryErros( $query, $e->getCode(), $e->getMessage() );
			}
			throw new DbException( 'Database error!', DbException::DB_QUERY_ERROR );
		}

		/**
		 * Reset the parameters
		 */
		$this->parameters = array();
	}


	public function bindMore( $parray )
	{
		if( !count( $this->parameters ) && is_array( $parray ) ) {
			$columns = array_keys( $parray );
			foreach( $columns as $i => &$column ) {
				$this->bind( $column, $parray[ $column ] );
			}
		}
	}

	public function bind( $para, $value )
	{
		$this->parameters[ sizeof( $this->parameters ) ] = [ ":" . $para, $value ];
	}


	public function CloseConnection()
	{
		$this->pdo = null;
	}


	/**
	 * @param $queryString
	 * @return string
	 */
	public static function getQueryStatement( $queryString )
	{
		$queryString = trim( $queryString );

		if( $queryString === '' ) {
			return self::QUERY_TYPE_EMPTY;
		}

		if( preg_match( '/^(select|insert|update|delete|replace|show|desc|explain)[\s]+/i', $queryString, $matches ) ) {
			switch( strtolower( $matches[ 1 ] ) ) {
				case 'select':
					return self::QUERY_TYPE_SELECT;
				case 'insert':
					return self::QUERY_TYPE_INSERT;
				case 'update':
					return self::QUERY_TYPE_UPDATE;
				case 'delete':
					return self::QUERY_TYPE_DELETE;
				case 'replace':
					return self::QUERY_TYPE_REPLACE;
				case 'explain':
					return self::QUERY_TYPE_EXPLAIN;
				default:
					return self::QUERY_TYPE_OTHER;
			}
		}
		else {
			return self::QUERY_TYPE_OTHER;
		}
	}

	/**
	 * @param array $arr
	 * @return bool
	 */
	public static function isArrayAssoc( array $arr )
	{
		if( array() === $arr ) return false;

		return array_keys( $arr ) !== range( 0, count( $arr ) - 1 );
	}


	/**
	 * @return DbService
	 */
	public static function getInstance()
	{
		if( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}