<?php
/**
 * Created by PhpStorm.
 * User: Adi
 * Date: 2/24/2019
 * Time: 6:54 PM
 */

namespace Qpdb\QueryBuilder\Abstracts;


use Qpdb\PdoWrapper\PdoWrapperService;
use Qpdb\QueryBuilder\Dependencies\QueryException;
use Qpdb\QueryBuilder\QueryBuild;

abstract class AbstractTableDao
{

	/**
	 * @var string
	 */
	protected $table;

	/**
	 * @var array
	 */
	protected $primary;

	/**
	 * @var string
	 */
	protected $orderField;

	/**
	 * @var integer
	 */
	protected $insertId;


	/**
	 * AbstractTableCrud constructor.
	 */
	public function __construct() {
		$this->table = $this->getTableName();
		$this->primary = (array)$this->getPrimaryKey();
		$this->orderField = $this->getOrderField();
	}


	/**
	 * @return string
	 */
	abstract protected function getTableName();

	/**
	 * @return string|array
	 */
	abstract protected function getPrimaryKey();

	/**
	 * @return string
	 */
	abstract protected function getOrderField();


	/**
	 * @param       $id
	 * @param array $fields
	 * @return array|bool
	 * @throws QueryException
	 */
	public function getRowById( $id, array $fields = [] ) {
		$conditions = $this->getPrimaryKeyConditions( $id );
		$result = QueryBuild::select( $this->table )->fields( $fields );
		foreach ( $conditions as $field => $value )
			$result->whereEqual( $field, $value );

		return $result->first()->execute();
	}

	/**
	 * @param       $fieldName
	 * @param       $fieldValue
	 * @param array $fields
	 * @return array
	 * @throws QueryException
	 */
	public function getFirstRowByCondition( $fieldName, $fieldValue, $fields = [] ) {
		return QueryBuild::select( $this->table )
			->fields( $fields )
			->whereEqual( $fieldName, $fieldValue )
			->first()
			->execute();
	}


	/**
	 * @param $id
	 * @return array|int|null
	 * @throws QueryException
	 */
	public function deleteRowById( $id ) {
		$conditions = $this->getPrimaryKeyConditions( $id );
		$result = QueryBuild::delete( $this->table );
		foreach ( $conditions as $field => $value )
			$result->whereEqual( $field, $value );

		return $result->execute();
	}

	/**
	 * @param       $id
	 * @param array $arrayUpdater
	 * @return array|int|null
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function updateRowById( $id, array $arrayUpdater ) {
		$conditions = $this->getPrimaryKeyConditions( $id );
		$result = QueryBuild::update( $this->table );
		foreach ( $conditions as $field => $value )
			$result->whereEqual( $field, $value );
		$result->setFieldsByArray( $arrayUpdater );

		return $result->execute();
	}


	/**
	 * @param array $arrayValues
	 * @return bool|mixed|\PDOStatement
	 * @throws QueryException
	 */
	public function insertRow( array $arrayValues ) {
		$result = QueryBuild::insert( $this->table )->setFieldsByArray( $arrayValues )->execute();
		$this->insertId = PdoWrapperService::getInstance()->lastInsertId();
		return $result;
	}

	/**
	 * @param bool $nullToZero
	 * @return null|mixed
	 * @throws QueryException
	 */
	public function getMaxOrder( $nullToZero = false ) {
		if ( empty( $this->orderField ) )
			throw new QueryException( 'Order field is not defined' );

		$result = QueryBuild::select( $this->table )
			->fieldExpression( "MAX(`{$this->orderField}`)", "max_order_column_alias" )
			->first()
			->column( 'max_order_column_alias' )
			->execute();

		if ( null === $result && $nullToZero ) {
			return 0;
		}

		return $result;
	}

	/**
	 * @param array $updates_ord
	 * @return bool|\PDOStatement
	 * @throws QueryException
	 */
	public function saveOrder( $updates_ord = array() ) {
		if ( empty( $this->orderField ) )
			throw new QueryException( 'Order field is not defined' );

		$query = /** @lang text */
			"UPDATE `{$this->table}` SET `{$this->orderField}` = CASE `{$this->primary[0]}` \r\n";
		foreach ( $updates_ord as $position => $id ) {
			$pos = $position + 1;
			$query .= " WHEN '$id' THEN '$pos' \r\n";
		}
		$query .= "ELSE `{$this->orderField}` END";

		return PdoWrapperService::getInstance()->query( $query, [] );
	}


	/**
	 * @param mixed
	 * @return array
	 * @throws QueryException
	 */
	protected function getPrimaryKeyConditions( $id ) {

		$id = (array)$id;

		if ( count( $this->primary ) !== count( $id ) )
			throw new QueryException( 'Invalid primary key', QueryException::QUERY_CRUD_INVALID_PRIMARY );

		$conditions = [];

		foreach ( $this->primary as $index => $key )
			$conditions[ $key ] = $id[ $index ];

		return $conditions;
	}

	/**
	 * @return int
	 */
	public function getInsertId(): int {
		return $this->insertId;
	}


}