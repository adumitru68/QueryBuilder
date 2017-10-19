<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 4/22/2017 11:40 PM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryException;
use Qpdb\QueryBuilder\Dependencies\QueryHelper;
use Qpdb\QueryBuilder\Dependencies\QueryStructure;

trait Limit
{

	use Objects;


	/**
	 * @param int $limit
	 * @param null $offset
	 * @return $this
	 * @throws QueryException
	 */
	public function limit( $limit = 0, $offset = null )
	{
		$limit = trim( $limit );

		if ( !QueryHelper::isInteger( $limit ) )
			throw new QueryException( 'Invalid Limit value', QueryException::QUERY_ERROR_INVALID_LIMIT );

		if ( $limit == 0 )
			throw new QueryException( 'Invalid Limit zero', QueryException::QUERY_ERROR_INVALID_LIMIT_ZERO );

		if ( is_null( $offset ) ) {
			$this->queryStructure->setElement( QueryStructure::LIMIT, $limit );

			return $this;
		}

		$offset = trim( $offset );

		if ( !QueryHelper::isInteger( $offset ) )
			throw new QueryException( 'Invalid Limit offset', QueryException::QUERY_ERROR_INVALID_LIMIT_OFFSET );

		$this->queryStructure->setElement( QueryStructure::LIMIT, $offset . ',' . $limit );

		return $this;
	}

	private function getLimitSyntax()
	{
		if ( !$this->queryStructure->getElement( QueryStructure::LIMIT ) )
			return '';

		return 'LIMIT ' . $this->queryStructure->getElement( QueryStructure::LIMIT );
	}

}

