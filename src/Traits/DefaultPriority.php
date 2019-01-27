<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 8/1/2017
 * Time: 4:43 PM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryStructure;

/**
 * Trait DefaultPriority
 * @package Qpdb\QueryBuilder\Traits
 * @property  QueryStructure $queryStructure
 */
trait DefaultPriority
{

	/**
	 * @return $this
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function defaultPriority()
	{
		$this->queryStructure->setElement( QueryStructure::PRIORITY, '' );

		return $this;
	}

}