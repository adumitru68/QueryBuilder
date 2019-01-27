<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 8/1/2017
 * Time: 4:57 PM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryStructure;

/**
 * Trait LowPriority
 * @package Qpdb\QueryBuilder\Traits
 * @property QueryStructure $queryStructure
 */
trait LowPriority
{

	/**
	 * @return $this
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function lowPriority()
	{
		$this->queryStructure->setElement( QueryStructure::PRIORITY, 'LOW_PRIORITY' );

		return $this;
	}

}