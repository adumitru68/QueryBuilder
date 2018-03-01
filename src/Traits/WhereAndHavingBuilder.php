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
	 * @var string|array
	 */
	private $temporaryParam;

	/**
	 * @var string
	 */
	private $temporaryGlue;

	/**
	 * @var string;
	 */
	private $temporaryClauseType;


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

		$this->temporaryParam = $this->validateWhereParam( $param );
		$this->temporaryGlue = $glue;
		$this->temporaryClauseType = $clauseType;

		$this->buildCondition();

		return $this;
	}

	private function buildCondition()
	{
		$operator = $this->temporaryParam[ 2 ];

		switch ( $operator ) {
			case 'BETWEEN':
			case 'NOT BETWEEN':
			case '!BETWEEN':
				$this->makeBetweenCondition();
				break;
			case 'IN':
			case 'NOT IN':
			case '!IN':
				$this->makeInCondition();
				break;
			default:
				$valuePdoString = $this->queryStructure->bindParam( $this->temporaryParam[ 0 ], $this->temporaryParam[ 1 ] );
				$body = $this->temporaryParam[ 0 ] . ' ' . $operator . ' ' . $valuePdoString;
				$this->registerCondition( $body );
				break;
		}
	}

	private function makeBetweenCondition()
	{
		$field = $this->temporaryParam[ 0 ];
		$value = $this->temporaryParam[ 1 ];
		$operator = $this->temporaryParam[ 2 ];

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
		$this->registerCondition( $body );
	}

	private function makeInCondition()
	{
		if ( is_a( $this->temporaryParam[ 1 ], QuerySelect::class ) )
			$this->inQuerySelect();
		elseif ( is_array( $this->temporaryParam[ 1 ] ) )
			$this->inArray();
	}

	private function inQuerySelect()
	{
		$field = $this->temporaryParam[ 0 ];
		/** @var QuerySelect $subquerySelect */
		$subquerySelect = $this->temporaryParam[ 1 ];
		$operator = $this->temporaryParam[ 2 ];
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
		$this->registerCondition( $body );

	}

	private function inArray()
	{
		$field = $this->temporaryParam[ 0 ];
		$value = $this->temporaryParam[ 1 ];
		$operator = $this->temporaryParam[ 2 ];

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
		$this->registerCondition( $body );
	}

	/**
	 * @param string|array $body
	 */
	private function registerCondition( $body )
	{
		$this->queryStructure->setElement( $this->temporaryClauseType, array( 'glue' => $this->temporaryGlue, 'body' => $body, 'type' => 'cond' ) );
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

		$where = strtoupper( $clauseType ) . ' ' . $where;

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

		$param[ 0 ] = $this->queryStructure->prepare( $param[ 0 ] );
		$param[ 2 ] = trim( strtoupper( $param[ 2 ] ) );
		$param[ 2 ] = QueryHelper::clearMultipleSpaces( $param[ 2 ] );

		return $param;
	}

}