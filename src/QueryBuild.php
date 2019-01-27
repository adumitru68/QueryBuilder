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
use Qpdb\QueryBuilder\Statements\Transaction;

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
	 * @throws Dependencies\QueryException
	 */
	public static function select( $table )
	{
		return new QuerySelect( new QueryBuild( 0 ), $table );
	}

	/**
	 * @param $table
	 * @return QueryUpdate
	 * @throws Dependencies\QueryException
	 */
	public static function update( $table )
	{
		return new QueryUpdate( new QueryBuild( 0 ), $table );
	}

	/**
	 * @param $table
	 * @return QueryInsert
	 * @throws Dependencies\QueryException
	 */
	public static function insert( $table )
	{
		return new QueryInsert( new QueryBuild( 0 ), $table );
	}

	/**
	 * @param $table
	 * @return QueryDelete
	 * @throws Dependencies\QueryException
	 */
	public static function delete( $table )
	{
		return new QueryDelete( new QueryBuild( 0 ), $table );
	}

	/**
	 * @param $query
	 * @return QueryCustom
	 * @throws Dependencies\QueryException
	 */
	public static function query( $query )
	{
		return new QueryCustom( new QueryBuild( 1 ), $query );
	}

	/**
	 * @return Transaction
	 */
	public static function transaction()
	{
		return new Transaction();
	}

	/**
	 * @return integer
	 */
	public function getType()
	{
		return $this->queryType;
	}

}