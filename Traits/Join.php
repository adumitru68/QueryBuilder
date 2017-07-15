<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 5/12/2017 9:20 PM
 */

namespace Adi\QueryBuilder\Traits;


use Adi\QueryBuilder\Dependencies\QueryStructure;
use Adi\QueryBuilder\Dependencies\QueryHelper;

trait Join
{

	use Objects;


	/**
	 * @param $tableJoin
	 * @param $onLeft
	 * @param null $onRight
	 * @return $this
	 */
	public function innerJoin( $tableJoin, $onLeft, $onRight = null )
	{
		return $this->makeJoin( 'INNER JOIN', $tableJoin, $onLeft, $onRight );
	}

	/**
	 * @param $tableJoin
	 * @param $onLeft
	 * @param null $onRight
	 * @return $this
	 */
	public function leftJoin( $tableJoin, $onLeft, $onRight = null )
	{
		return $this->makeJoin( 'LEFT JOIN', $tableJoin, $onLeft, $onRight );
	}

	/**
	 * @param $tableJoin
	 * @param $onLeft
	 * @param null $onRight
	 * @return $this
	 */
	public function leftOuterJoin( $tableJoin, $onLeft, $onRight = null )
	{
		return $this->makeJoin( 'LEFT OUTER JOIN', $tableJoin, $onLeft, $onRight );
	}

	/**
	 * @param $tableJoin
	 * @param $onLeft
	 * @param null $onRight
	 * @return $this
	 */
	public function rightJoin( $tableJoin, $onLeft, $onRight = null )
	{
		return $this->makeJoin( 'RIGHT JOIN', $tableJoin, $onLeft, $onRight );
	}

	/**
	 * @param $tableJoin
	 * @param $onLeft
	 * @param null $onRight
	 * @return $this
	 */
	public function rightOuterJoin($tableJoin, $onLeft, $onRight = null )
	{
		return $this->makeJoin( 'RIGHT OUTER JOIN', $tableJoin, $onLeft, $onRight );
	}

	/**
	 * @param $tableJoin
	 * @param $onLeft
	 * @param null $onRight
	 * @return $this
	 */
	public function fullJoin($tableJoin, $onLeft, $onRight = null )
	{
		return $this->makeJoin( 'FULL JOIN', $tableJoin, $onLeft, $onRight );
	}

	/**
	 * @param $tableJoin
	 * @param $onLeft
	 * @param null $onRight
	 * @return $this
	 */
	public function fullOuterJoin($tableJoin, $onLeft, $onRight = null )
	{
		return $this->makeJoin( 'FULL OUTER JOIN', $tableJoin, $onLeft, $onRight );
	}

	/**
	 * @param $stringJoin
	 * @return $this
	 */
	public function join ( $stringJoin )
	{
		$this->queryStructure->setElement( QueryStructure::JOIN, $stringJoin );
		return $this;
	}

	/**
	 * @param $typeJoin
	 * @param $tableJoin
	 * @param $onLeft
	 * @param null $onRight
	 * @return $this
	 */
	private function makeJoin ( $typeJoin, $tableJoin, $onLeft, $onRight = null )
	{
		$join = $typeJoin . ' ' . $tableJoin;

		if(is_null($onRight))
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