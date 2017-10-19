<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 4/22/2017 4:08 AM
 */

namespace Qpdb\QueryBuilder;


use Qpdb\QueryBuilder\Statements\QueryCustom;
use Qpdb\QueryBuilder\Statements\QueryDelete;
use Qpdb\QueryBuilder\Statements\QueryInsert;
use Qpdb\QueryBuilder\Statements\QuerySelect;
use Qpdb\QueryBuilder\Statements\QueryUpdate;

class QueryBuild
{

	/**
	 * @var integer
	 */
	private $queryType;

	/**
	 * QueryBuild constructor.
	 * @param $queryType
	 */
	protected function __construct( $queryType )
	{
		$this->queryType = $queryType;
	}

	/**
	 * @param $table
	 * @return QuerySelect
	 */
	public static function select( $table )
	{
		return new QuerySelect( new QueryBuild( 0 ), $table );
	}

	/**
	 * @param $table
	 * @return QueryUpdate
	 */
	public static function update( $table )
	{
		return new QueryUpdate( new QueryBuild( 0 ), $table );
	}

	/**
	 * @param $table
	 * @return QueryInsert
	 */
	public static function insert( $table )
	{
		return new QueryInsert( new QueryBuild( 0 ), $table );
	}

	/**
	 * @param $table
	 * @return QueryDelete
	 */
	public static function delete( $table )
	{
		return new QueryDelete( new QueryBuild( 0 ), $table );
	}

	/**
	 * @param $query
	 * @return QueryCustom
	 */
	public static function query( $query )
	{
		return new QueryCustom( new QueryBuild( 1 ), $query );
	}

	/**
	 * @return integer
	 */
	public function getType()
	{
		return $this->queryType;
	}

}