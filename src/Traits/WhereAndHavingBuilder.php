<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 9/29/2017
 * Time: 4:20 PM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryException;
use Qpdb\QueryBuilder\Dependencies\QueryHelper;
use Qpdb\QueryBuilder\Dependencies\QueryStructure;
use Qpdb\QueryBuilder\Statements\QuerySelect;

trait WhereAndHavingBuilder
{

	use Objects;


	/**
	 * @param $param
	 * @param string $glue
	 * @param $clauseType
	 * @return $this
	 */
	protected function createCondition( $param, $glue = 'AND', $clauseType )
	{

		if ( !is_array( $param ) ) {
			$this->queryStructure->setElement( $clauseType, array( 'glue' => $glue, 'body' => trim( $param ), 'type' => 'cond' ) );

			return $this;
		}

		$param = $this->validateWhereParam( $param );

		$field = $param[ 0 ];
		$value = $param[ 1 ];
		$operator = $param[ 2 ];

		switch ( $operator ) {
			case 'BETWEEN':
			case 'NOT BETWEEN':
			case '!BETWEEN':
				$min = $value[ 0 ];
				$max = $value[ 1 ];
				$body = [
					$field,
					$operator,
					$this->queryStructure->bindParam( 'min', $min ),
					'AND',
					$this->queryStructure->bindParam( 'max', $max )
				];
				$body = implode( ' ', $body );
				$this->queryStructure->setElement( $clauseType, array( 'glue' => $glue, 'body' => $body, 'type' => 'cond' ) );
				break;

			case 'IN':
			case 'NOT IN':
			case '!IN':
				if ( is_a( $value, QuerySelect::class ) )
					return $this->inSelectObject( $field, $value, $operator, $glue, $clauseType );
				elseif ( is_array( $value ) )
					return $this->inArray( $field, $value, $operator, $glue, $clauseType );
				break;

			default:
				$valuePdoString = $this->queryStructure->bindParam( $field, $value );
				$body = $field . ' ' . $operator . ' ' . $valuePdoString;
				$this->queryStructure->setElement( $clauseType, array( 'glue' => $glue, 'body' => $body, 'type' => 'cond' ) );

		}

		return $this;

	}


	/**
	 * @param $field
	 * @param QuerySelect $subquerySelect
	 * @param $operator
	 * @param string $glue
	 * @param $clauseType
	 * @return $this
	 */
	private function inSelectObject( $field, QuerySelect $subquerySelect, $operator, $glue = 'AND', $clauseType )
	{
		$subquerySelectParams = $subquerySelect->getBindParams();
		foreach ( $subquerySelectParams as $key => $value ) {
			$this->queryStructure->setParams( $key, $value );
		}
		$body = [
			$field,
			$operator,
			'( ',
			$subquerySelect->getSyntax(),
			' )'
		];
		$body = implode( ' ', $body );
		$this->queryStructure->setElement( $clauseType, array( 'glue' => $glue, 'body' => $body, 'type' => 'cond' ) );

		return $this;
	}

	/**
	 * @param $field
	 * @param array $value
	 * @param $operator
	 * @param $glue
	 * @param $clauseType
	 * @return $this
	 */
	private function inArray( $field, array $value, $operator, $glue, $clauseType )
	{
		$pdoArray = array();
		foreach ( $value as $item ) {
			$pdoArray[] = $this->queryStructure->bindParam( 'a', $item );
		}
		$body = [
			$field,
			$operator,
			'( ' . implode( ', ', $pdoArray ) . ' )'
		];
		$body = implode( ' ', $body );
		$body = QueryHelper::clearMultipleSpaces( $body );
		$this->queryStructure->setElement( $clauseType, array( 'glue' => $glue, 'body' => $body, 'type' => 'cond' ) );

		return $this;
	}


	/**
	 * @return bool|mixed|string
	 */
	private function getWhereSyntax()
	{
		return $this->getWhereAndHavingSyntax( QueryStructure::WHERE );
	}

	/**
	 * @return bool|mixed|string
	 */
	private function getHavingSyntax()
	{
		return $this->getWhereAndHavingSyntax( QueryStructure::HAVING );
	}

	/**
	 * @param $clauseType
	 * @return bool|mixed|string
	 */
	private function getWhereAndHavingSyntax( $clauseType )
	{
		if ( count( $this->queryStructure->getElement( $clauseType ) ) == 0 )
			return '';

		$where = '';
		$last_type = 'where_start';
		foreach ( $this->queryStructure->getElement( $clauseType ) as $where_cond ) {
			$glue = $where_cond[ 'glue' ];
			if ( $last_type == 'where_start' || $last_type == 'start_where_group' ) {
				$glue = '';
			}
			$where .= ' ' . $glue . ' ' . $where_cond[ 'body' ];
			$last_type = $where_cond[ 'type' ];
		}

		if ( $this->queryStructure->getElement( $clauseType . '_invert' ) ) {
			$where = ' NOT ( ' . $where . ' ) ';
		}

		$where = 'WHERE ' . $where;

		return QueryHelper::clearMultipleSpaces( $where );
	}

	/**
	 * @param $param
	 * @return array
	 * @throws QueryException
	 */
	private function validateWhereParam( $param )
	{
		if ( count( $param ) < 2 )
			throw new QueryException( 'Invalid where array!', QueryException::QUERY_ERROR_WHERE_INVALID_PARAM_ARRAY );

		if ( count( $param ) == 2 )
			$param[] = '=';

		$param[ 2 ] = trim( strtoupper( $param[ 2 ] ) );
		$param[ 2 ] = QueryHelper::clearMultipleSpaces( $param[ 2 ] );

		return $param;
	}

}