<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 9/17/2017
 * Time: 5:16 AM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryStructure;

/**
 * Trait ColumnValidation
 * @package Qpdb\QueryBuilder\Traits
 * @property  QueryStructure $queryStructure
 */
trait ColumnValidation
{

	protected function validateColumn( $columnName, array $allowed )
	{
		if ( is_integer( $columnName ) )
			return true;

		if ( !count( $allowed ) )
			return true;

		return false;
	}


}