<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 4/25/2017 1:13 AM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryStructure;

trait Where
{

	use Objects;


	/**
	 * @param $field
	 * @param $value
	 * @param $glue
	 * @return $this
	 */
	public function whereEqual( $field, $value, $glue = 'AND' )
	{
		return $this->where( array( $field, $value, '=' ), $glue );
	}

	/**
	 * @param $field
	 * @param $value
	 * @return $this
	 */
	public function orWhereEqual( $field, $value )
	{
		return $this->where( array( $field, $value, '=' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $value
	 * @param string $glue
	 * @return $this
	 */
	public function whereNotEqual( $field, $value, $glue = 'AND' )
	{
		return $this->where( array( $field, $value, '<>' ), $glue );
	}

	/**
	 * @param $field
	 * @param $value
	 * @return $this
	 */
	public function orWhereNotEqual( $field, $value )
	{
		return $this->where( array( $field, $value, '<>' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $value
	 * @param string $glue
	 * @return $this
	 */
	public function whereLessThan( $field, $value, $glue = 'AND' )
	{
		return $this->where( array( $field, $value, '<' ), $glue );
	}

	/**
	 * @param $field
	 * @param $value
	 * @return $this
	 */
	public function orWhereLessThan( $field, $value )
	{
		return $this->where( array( $field, $value, '<' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $value
	 * @param string $glue
	 * @return $this
	 */
	public function whereLessThanOrEqual( $field, $value, $glue = 'AND' )
	{
		return $this->where( array( $field, $value, '<=' ), $glue );
	}

	/**
	 * @param $field
	 * @param $value
	 * @return $this
	 */
	public function orWhereLessThanOrEqual( $field, $value )
	{
		return $this->where( array( $field, $value, '<=' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $value
	 * @param string $glue
	 * @return $this
	 */
	public function whereGreaterThan( $field, $value, $glue = 'AND' )
	{
		return $this->where( array( $field, $value, '>' ), $glue );
	}

	/**
	 * @param $field
	 * @param $value
	 * @return $this
	 */
	public function orWhereGreaterThan( $field, $value )
	{
		return $this->where( array( $field, $value, '>' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $value
	 * @param string $glue
	 * @return $this
	 */
	public function whereGreaterThanOrEqual( $field, $value, $glue = 'AND' )
	{
		return $this->where( array( $field, $value, '>=' ), $glue );
	}

	/**
	 * @param $field
	 * @param $value
	 * @return $this
	 */
	public function orWhereGreaterThanOrEqual( $field, $value )
	{
		return $this->where( array( $field, $value, '>=' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $value
	 * @param string $glue
	 * @return $this
	 */
	public function whereLike( $field, $value, $glue = 'AND' )
	{
		return $this->where( array( $field, $value, 'LIKE' ), $glue );
	}

	/**
	 * @param $field
	 * @param $value
	 * @return $this
	 */
	public function orWhereLike( $field, $value )
	{
		return $this->where( array( $field, $value, 'LIKE' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $value
	 * @param string $glue
	 * @return $this
	 */
	public function whereNotLike( $field, $value, $glue = 'AND' )
	{
		return $this->where( array( $field, $value, 'NOT LIKE' ), $glue );
	}

	/**
	 * @param $field
	 * @param $value
	 * @return $this
	 */
	public function orWhereNotLike( $field, $value )
	{
		return $this->where( array( $field, $value, 'NOT LIKE' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $min
	 * @param $max
	 * @param string $glue
	 * @return $this
	 */
	public function whereBetween( $field, $min, $max, $glue = 'AND' )
	{
		return $this->where( array( $field, array( $min, $max ), 'BETWEEN' ), $glue );
	}

	/**
	 * @param $field
	 * @param $min
	 * @param $max
	 * @return $this
	 */
	public function orWhereBetween( $field, $min, $max )
	{
		return $this->where( array( $field, array( $min, $max ), 'BETWEEN' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $min
	 * @param $max
	 * @param string $glue
	 * @return $this
	 */
	public function whereNotBetween( $field, $min, $max, $glue = 'AND' )
	{
		return $this->where( array( $field, array( $min, $max ), 'NOT BETWEEN' ), $glue );
	}

	/**
	 * @param $field
	 * @param $min
	 * @param $max
	 * @return $this
	 */
	public function orWhereNotBetween( $field, $min, $max )
	{
		return $this->where( array( $field, array( $min, $max ), 'NOT BETWEEN' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $value
	 * @param string $glue
	 * @return $this
	 */
	public function whereIn( $field, $value, $glue = 'AND' )
	{
		return $this->where( array( $field, $value, 'IN' ), $glue );
	}

	/**
	 * @param $field
	 * @param $value
	 * @return $this
	 */
	public function orWhereIn( $field, $value )
	{
		return $this->where( array( $field, $value, 'IN' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $value
	 * @param string $glue
	 * @return $this
	 */
	public function whereNotIn( $field, $value, $glue = 'AND' )
	{
		return $this->where( array( $field, $value, 'NOT IN' ), $glue );
	}

	/**
	 * @param $field
	 * @param $value
	 * @return $this
	 */
	public function orWhereNotIn( $field, $value )
	{
		return $this->where( array( $field, $value, 'NOT IN' ), 'OR' );
	}

	/**
	 * @param $whereString
	 * @param array $bindParams
	 * @param string $glue
	 * @return $this
	 */
	public function whereExpression( $whereString, array $bindParams = [], $glue = 'AND' )
	{
		$whereString = $this->queryStructure->bindParamsExpression( $whereString, $bindParams );

		return $this->where( $whereString, $glue );
	}

	/**
	 * @param $whereString
	 * @param array $bindParams
	 * @return $this
	 */
	public function orWhereExpression( $whereString, array $bindParams = [] )
	{
		$whereString = $this->queryStructure->bindParamsExpression( $whereString, $bindParams );

		return $this->where( $whereString, 'OR' );
	}

	/**
	 * @return $this
	 */
	public function whereInvertResult()
	{
		$this->queryStructure->setElement( QueryStructure::WHERE_INVERT, 1 );

		return $this;
	}

	/**
	 * @param string $glue
	 * @return $this
	 */
	public function whereGroup( $glue = 'AND' )
	{
		$this->queryStructure->setElement( QueryStructure::WHERE, array( 'glue' => $glue, 'body' => '(', 'type' => 'start_where_group' ) );

		return $this;
	}

	/**
	 * @return $this
	 */
	public function orWhereGroup()
	{
		return $this->whereGroup( 'OR' );
	}

	/**
	 * @return $this
	 */
	public function whereGroupEnd()
	{
		$this->queryStructure->setElement( QueryStructure::WHERE, array( 'glue' => '', 'body' => ')', 'type' => 'end_where_group' ) );

		return $this;
	}

	/**
	 * @return $this
	 */
	public function ignoreWhereTrigger()
	{
		$this->queryStructure->setElement( QueryStructure::WHERE_TRIGGER, 0 );

		return $this;
	}


	/**
	 * @param string|array $param
	 * @param string $glue
	 * @return $this
	 */
	private function where( $param, $glue = 'AND' )
	{
		return $this->/** @scrutinizer ignore-call */
		createCondition( $param, $glue, QueryStructure::WHERE );
	}

}