<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 4/25/2017 1:13 AM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryStructure;

/**
 * Trait Having
 * @package Qpdb\QueryBuilder\Traits
 * @property QueryStructure $queryStructure
 */
trait Having
{

	/**
	 * @param $field
	 * @param $value
	 * @param $glue
	 * @return $this
	 */
	public function havingEqual( $field, $value, $glue = 'AND' )
	{
		return $this->having( array( $field, $value, '=' ), $glue );
	}

	/**
	 * @param $field
	 * @param $value
	 * @return $this
	 */
	public function orHavingEqual( $field, $value )
	{
		return $this->having( array( $field, $value, '=' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $value
	 * @param string $glue
	 * @return $this
	 */
	public function havingNotEqual( $field, $value, $glue = 'AND' )
	{
		return $this->having( array( $field, $value, '<>' ), $glue );
	}

	/**
	 * @param $field
	 * @param $value
	 * @return $this
	 */
	public function orHavingNotEqual( $field, $value )
	{
		return $this->having( array( $field, $value, '<>' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $value
	 * @param string $glue
	 * @return $this
	 */
	public function havingLessThan( $field, $value, $glue = 'AND' )
	{
		return $this->having( array( $field, $value, '<' ), $glue );
	}

	/**
	 * @param $field
	 * @param $value
	 * @return $this
	 */
	public function orHavingLessThan( $field, $value )
	{
		return $this->having( array( $field, $value, '<' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $value
	 * @param string $glue
	 * @return $this
	 */
	public function havingLessThanOrEqual( $field, $value, $glue = 'AND' )
	{
		return $this->having( array( $field, $value, '<=' ), $glue );
	}

	/**
	 * @param $field
	 * @param $value
	 * @return $this
	 */
	public function orHavingLessThanOrEqual( $field, $value )
	{
		return $this->having( array( $field, $value, '<=' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $value
	 * @param string $glue
	 * @return $this
	 */
	public function havingGreaterThan( $field, $value, $glue = 'AND' )
	{
		return $this->having( array( $field, $value, '>' ), $glue );
	}

	/**
	 * @param $field
	 * @param $value
	 * @return $this
	 */
	public function orHavingGreaterThan( $field, $value )
	{
		return $this->having( array( $field, $value, '>' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $value
	 * @param string $glue
	 * @return $this
	 */
	public function havingGreaterThanOrEqual( $field, $value, $glue = 'AND' )
	{
		return $this->having( array( $field, $value, '>=' ), $glue );
	}

	/**
	 * @param $field
	 * @param $value
	 * @return $this
	 */
	public function orHavingGreaterThanOrEqual( $field, $value )
	{
		return $this->having( array( $field, $value, '>=' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $value
	 * @param string $glue
	 * @return $this
	 */
	public function havingLike( $field, $value, $glue = 'AND' )
	{
		return $this->having( array( $field, $value, 'LIKE' ), $glue );
	}

	/**
	 * @param $field
	 * @param $value
	 * @return $this
	 */
	public function orHavingLike( $field, $value )
	{
		return $this->having( array( $field, $value, 'LIKE' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $value
	 * @param string $glue
	 * @return $this
	 */
	public function havingNotLike( $field, $value, $glue = 'AND' )
	{
		return $this->having( array( $field, $value, 'NOT LIKE' ), $glue );
	}

	/**
	 * @param $field
	 * @param $value
	 * @return $this
	 */
	public function orHavingNotLike( $field, $value )
	{
		return $this->having( array( $field, $value, 'NOT LIKE' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $min
	 * @param $max
	 * @param string $glue
	 * @return $this
	 */
	public function havingBetween( $field, $min, $max, $glue = 'AND' )
	{
		return $this->having( array( $field, array( $min, $max ), 'BETWEEN' ), $glue );
	}

	/**
	 * @param $field
	 * @param $min
	 * @param $max
	 * @return $this
	 */
	public function orHavingBetween( $field, $min, $max )
	{
		return $this->having( array( $field, array( $min, $max ), 'BETWEEN' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $min
	 * @param $max
	 * @param string $glue
	 * @return $this
	 */
	public function havingNotBetween( $field, $min, $max, $glue = 'AND' )
	{
		return $this->having( array( $field, array( $min, $max ), 'NOT BETWEEN' ), $glue );
	}

	/**
	 * @param $field
	 * @param $min
	 * @param $max
	 * @return $this
	 */
	public function orHavingNotBetween( $field, $min, $max )
	{
		return $this->having( array( $field, array( $min, $max ), 'NOT BETWEEN' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $value
	 * @param string $glue
	 * @return $this
	 */
	public function havingIn( $field, $value, $glue = 'AND' )
	{
		return $this->having( array( $field, $value, 'IN' ), $glue );
	}

	/**
	 * @param $field
	 * @param $value
	 * @return $this
	 */
	public function orHavingIn( $field, $value )
	{
		return $this->having( array( $field, $value, 'IN' ), 'OR' );
	}

	/**
	 * @param $field
	 * @param $value
	 * @param string $glue
	 * @return $this
	 */
	public function havingNotIn( $field, $value, $glue = 'AND' )
	{
		return $this->having( array( $field, $value, 'NOT IN' ), $glue );
	}

	/**
	 * @param $field
	 * @param $value
	 * @return $this
	 */
	public function orHavingNotIn( $field, $value )
	{
		return $this->having( array( $field, $value, 'NOT IN' ), 'OR' );
	}

	/**
	 * @param $whereString
	 * @param array $bindParams
	 * @param string $glue
	 * @return $this
	 */
	public function havingExpression( $whereString, array $bindParams = [], $glue = 'AND' )
	{
		$whereString = $this->queryStructure->bindParamsExpression( $whereString, $bindParams );

		return $this->having( $whereString, $glue );
	}

	/**
	 * @param $whereString
	 * @param array $bindParams
	 * @return $this
	 */
	public function orHavingExpression( $whereString, array $bindParams = [] )
	{
		$whereString = $this->queryStructure->bindParamsExpression( $whereString, $bindParams );

		return $this->having( $whereString, 'OR' );
	}

	/**
	 * @return $this
	 */
	public function havingInvertResult()
	{
		$this->queryStructure->setElement( QueryStructure::HAVING_INVERT, 1 );

		return $this;
	}

	/**
	 * @param string $glue
	 * @return $this
	 */
	public function havingGroup( $glue = 'AND' )
	{
		$this->queryStructure->setElement( QueryStructure::HAVING, array( 'glue' => $glue, 'body' => '(', 'type' => 'start_where_group' ) );

		return $this;
	}

	/**
	 * @return $this
	 */
	public function orHavingGroup()
	{
		return $this->havingGroup( 'OR' );
	}

	/**
	 * @return $this
	 */
	public function havingGroupEnd()
	{
		$this->queryStructure->setElement( QueryStructure::HAVING, array( 'glue' => '', 'body' => ')', 'type' => 'end_where_group' ) );

		return $this;
	}

	/**
	 * @param $param
	 * @param string $glue
	 * @return $this
	 */
	private function having( $param, $glue = 'AND' )
	{
		return $this->createCondition( $param, $glue, QueryStructure::HAVING );
	}

}