<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 8/1/2017
 * Time: 4:48 PM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryStructure;

/**
 * Trait HighPriority
 * @package Qpdb\QueryBuilder\Traits
 * @property QueryStructure $queryStructure
 */
trait HighPriority
{

	/**
	 * @return $this
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function highPriority()
	{
		$this->queryStructure->setElement( QueryStructure::PRIORITY, 'HIGH_PRIORITY' );

		return $this;
	}

}