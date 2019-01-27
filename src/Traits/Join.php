<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 5/12/2017 9:20 PM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryHelper;
use Qpdb\QueryBuilder\Dependencies\QueryStructure;


/**
 * Trait Join
 * @package Qpdb\QueryBuilder\Traits
 * @property QueryStructure $queryStructure
 */
trait Join
{

	/**
	 * @param string $tableJoin
	 * @param string $onLeft
	 * @param string $onRight
	 * @return $this
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function innerJoin( $tableJoin, $onLeft, $onRight = null )
	{
		return $this->makeJoin( 'INNER JOIN', $tableJoin, $onLeft, $onRight );
	}

	/**
	 * @param string $tableJoin
	 * @param string $onLeft
	 * @param string $onRight
	 * @return $this
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function leftJoin( $tableJoin, $onLeft, $onRight = null )
	{
		return $this->makeJoin( 'LEFT JOIN', $tableJoin, $onLeft, $onRight );
	}

	/**
	 * @param string $tableJoin
	 * @param string $onLeft
	 * @param string $onRight
	 * @return $this
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function leftOuterJoin( $tableJoin, $onLeft, $onRight = null )
	{
		return $this->makeJoin( 'LEFT OUTER JOIN', $tableJoin, $onLeft, $onRight );
	}

	/**
	 * @param string $tableJoin
	 * @param string $onLeft
	 * @param string $onRight
	 * @return $this
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function rightJoin( $tableJoin, $onLeft, $onRight = null )
	{
		return $this->makeJoin( 'RIGHT JOIN', $tableJoin, $onLeft, $onRight );
	}

	/**
	 * @param string $tableJoin
	 * @param string $onLeft
	 * @param string $onRight
	 * @return $this
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function rightOuterJoin( $tableJoin, $onLeft, $onRight = null )
	{
		return $this->makeJoin( 'RIGHT OUTER JOIN', $tableJoin, $onLeft, $onRight );
	}

	/**
	 * @param string $tableJoin
	 * @param string $onLeft
	 * @param string $onRight
	 * @return $this
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function fullJoin( $tableJoin, $onLeft, $onRight = null )
	{
		return $this->makeJoin( 'FULL JOIN', $tableJoin, $onLeft, $onRight );
	}

	/**
	 * @param string $tableJoin
	 * @param string $onLeft
	 * @param string $onRight
	 * @return $this
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function fullOuterJoin( $tableJoin, $onLeft, $onRight = null )
	{
		return $this->makeJoin( 'FULL OUTER JOIN', $tableJoin, $onLeft, $onRight );
	}

	/**
	 * @param $stringJoin
	 * @return $this
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function join( $stringJoin )
	{
		$this->queryStructure->setElement( QueryStructure::JOIN, $stringJoin );

		return $this;
	}

	/**
	 * @param string $typeJoin
	 * @param string $tableJoin
	 * @param string $onLeft
	 * @param string $onRight
	 * @return $this
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	private function makeJoin( $typeJoin, $tableJoin, $onLeft, $onRight = null )
	{
		$join = $typeJoin . ' ' . $tableJoin;

		if ( is_null( $onRight ) )
			$join .= " USING ( $onLeft )";
		else
			$join .= " ON $onLeft = $onRight";

		$this->queryStructure->setElement( QueryStructure::JOIN, $join );

		return $this;
	}

	/**
	 * @return string
	 */
	private function getJoinSyntax()
	{
		$joinString = implode( ' ', $this->queryStructure->getElement( QueryStructure::JOIN ) );

		return QueryHelper::clearMultipleSpaces( $joinString );
	}

}