<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 4/30/2017 9:55 PM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryException;
use Qpdb\QueryBuilder\Dependencies\QueryHelper;
use Qpdb\QueryBuilder\Dependencies\QueryStructure;

trait GroupBy
{

	use Objects;


	/**
	 * @param $column
	 * @param array $allowedColumns
	 * @return $this
	 * @throws QueryException
	 */
	public function groupBy( $column, array $allowedColumns = [] )
	{
		$column = trim( $column );

		if ( !$this->validateColumn( $column, $allowedColumns ) )
			throw new QueryException( 'Invalid column name in GROUP BY clause', QueryException::QUERY_ERROR_INVALID_COLUMN_NAME );

		$this->queryStructure->setElement( QueryStructure::GROUP_BY, $column );

		return $this;
	}


	/**
	 * @param int|string $column
	 * @param array $allowedColumns
	 * @return $this
	 * @throws QueryException
	 */
	public function groupByDesc( $column, array $allowedColumns = [] )
	{
		$column = trim( $column );

		if ( !$this->validateColumn( $column, $allowedColumns ) )
			throw new QueryException( 'Invalid column name in GROUP BY clause', QueryException::QUERY_ERROR_INVALID_COLUMN_NAME );

		$this->queryStructure->setElement( QueryStructure::GROUP_BY, $column . ' DESC' );

		return $this;
	}


	/**
	 * @param $expression
	 * @return $this
	 */
	public function groupByExpression( $expression )
	{
		$this->queryStructure->setElement( QueryStructure::GROUP_BY, $expression );

		return $this;
	}


	/**
	 * @return string
	 */
	private function getGroupBySyntax()
	{
		if ( count( $this->queryStructure->getElement( QueryStructure::GROUP_BY ) ) )
			return 'GROUP BY ' . QueryHelper::implode( $this->queryStructure->getElement( QueryStructure::GROUP_BY ), ', ' );

		return '';
	}

}