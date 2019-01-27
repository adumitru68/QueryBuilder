<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 6/27/2017
 * Time: 8:47 AM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryStructure;

/**
 * Trait Ignore
 * @package Qpdb\QueryBuilder\Traits
 * @property QueryStructure $queryStructure
 */
trait Ignore
{

	/**
	 * @return $this
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function ignore()
	{
		$this->queryStructure->setElement( QueryStructure::IGNORE, 1 );

		return $this;
	}


}