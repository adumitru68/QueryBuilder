<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 5/26/2017 11:00 PM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryStructure;

/**
 * Trait Utilities
 * @package Qpdb\QueryBuilder\Traits
 * @property QueryStructure $queryStructure
 */
trait Utilities
{

	/**
	 * @return $this
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function explain()
	{
		$this->queryStructure->setElement( QueryStructure::EXPLAIN, 1 );

		return $this;
	}

	/**
	 * @return string
	 */
	protected function getExplainSyntax()
	{
		if ( $this->queryStructure->getElement( QueryStructure::EXPLAIN ) )
			return 'EXPLAIN';

		return '';
	}

	/**
	 * @param string $comment
	 * @return $this
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function withComment( $comment = '' )
	{
		$this->queryStructure->setElement( QueryStructure::QUERY_COMMENT, $comment );

		return $this;
	}

	/**
	 * @param string $identifier
	 * @return $this
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function withLogIdentifier( $identifier = null )
	{
		$this->queryStructure->setElement( QueryStructure::QUERY_IDENTIFIER, $identifier );

		return $this;
	}

}