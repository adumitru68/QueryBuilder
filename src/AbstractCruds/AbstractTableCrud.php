<?php
/**
 * Created by PhpStorm.
 * User: Adi
 * Date: 7/29/2018
 * Time: 12:58 AM
 */

namespace Qpdb\QueryBuilder\AbstractCruds;


use Qpdb\QueryBuilder\DB\DbService;
use Qpdb\QueryBuilder\Dependencies\QueryException;
use Qpdb\QueryBuilder\QueryBuild;

abstract class AbstractTableCrud
{

	/**
	 * @var string
	 */
	protected $table;

	/**
	 * @var string|array
	 */
	protected $primary;

	/**
	 * @var string
	 */
	protected $orderField;

	/**
	 * @var mixed
	 */
	protected $lastInsertId;


	abstract protected function setTable();

	abstract protected function setPrimaryKey();

	abstract protected function setOrderField();


	public function __construct()
	{
		$this->setTable();
		$this->setPrimaryKey();
		$this->setOrderField();

		if ( !is_array( $this->primary ) )
			$this->primary = [ $this->primary ];
	}

	/**
	 * @param $id
	 * @param array $fields
	 * @return array|bool
	 * @throws QueryException
	 */
	public function getRowById( $id, array $fields = [] )
	{
		$conditions = $this->getPrimaryKeyConditions( $id );
		$result = QueryBuild::select( $this->table )->fields( $fields );
		foreach ( $conditions as $field => $value )
			$result->whereEqual( $field, $value );

		return $result->first()->execute();
	}


	/**
	 * @param $id
	 * @return array|int|null
	 * @throws QueryException
	 */
	public function deleteRowById( $id )
	{
		$conditions = $this->getPrimaryKeyConditions( $id );
		$result = QueryBuild::delete( $this->table );
		foreach ( $conditions as $field => $value )
			$result->whereEqual( $field, $value );

		return $result->execute();
	}

	/**
	 * @param $id
	 * @param array $arrayUpdater
	 * @return array|int|null
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function updateRowById( $id, array $arrayUpdater )
	{
		$conditions = $this->getPrimaryKeyConditions( $id );
		$result = QueryBuild::update( $this->table );
		foreach ( $conditions as $field => $value )
			$result->whereEqual( $field, $value );
		$result->setFieldsByArray( $arrayUpdater );

		return $result->execute();
	}


	/**
	 * @param array $arrayValues
	 * @return array|int|null
	 */
	public function insertRow( array $arrayValues )
	{
		$result = QueryBuild::insert( $this->table )->setFieldsByArray( $arrayValues );
		$this->lastInsertId = DbService::getInstance()->getLastInsertId();

		return $result->execute();
	}


	/**
	 * @return mixed
	 */
	public function lastInsertId()
	{
		return $this->lastInsertId;
	}


	/**
	 * @param array $updates_ord
	 * @return int
	 * @throws QueryException
	 */
	public function saveOrder( $updates_ord = array() )
	{
		if ( empty( $this->orderField ) )
			throw new QueryException( 'Order field is not defined' );

		$query = /** @lang text */
			"UPDATE `{$this->table}` SET `{$this->orderField}` = CASE `{$this->primary[0]}` \r\n";
		foreach ( $updates_ord as $position => $id ) {
			$pos = $position + 1;
			$query .= " WHEN '$id' THEN '$pos' \r\n";
		}
		$query .= "ELSE `{$this->orderField}` END";

		return DbService::getInstance()->query( $query, [] );
	}


	/**
	 * @param mixed
	 * @return array
	 * @throws QueryException
	 */
	protected function getPrimaryKeyConditions( $id )
	{
		if ( !is_array( $id ) )
			$id = [ $id ];

		if ( count( $this->primary ) !== count( $id ) )
			throw new QueryException( 'Invalid primary key', QueryException::QUERY_CRUD_INVALID_PRIMARY );

		$conditions = [];

		foreach ( $this->primary as $index => $key )
			$conditions[ $key ] = $id[ $index ];

		return $conditions;
	}


}