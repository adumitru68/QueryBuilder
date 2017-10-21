<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 4/30/2017 4:47 PM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\DB\DbConnect;
use Qpdb\QueryBuilder\Dependencies\QueryHelper;
use Qpdb\QueryBuilder\Dependencies\QueryStructure;
use Qpdb\QueryBuilder\Statements\QueryStatementInterface;

trait Replacement
{

	use Objects;

	/**
	 * @param $syntax
	 * @param int|bool $withReplacement
	 * @return mixed|string
	 */
	private function getSyntaxReplace( $syntax, $withReplacement = QueryStatementInterface::REPLACEMENT_NONE )
	{
		$syntax = QueryHelper::clearMultipleSpaces( $syntax );

		if ( !$withReplacement )
			return $syntax;

		return $this->replaceValues( $syntax );
	}

	/**
	 * @param string $syntax
	 * @return string
	 */
	private function replaceValues( $syntax )
	{
		$bindParams = $this->queryStructure->getElement( QueryStructure::BIND_PARAMS );
		$search = array();
		$replace = array();
		foreach ( $bindParams as $key => $value ) {
			$search[] = ':' . $key;
			$replace[] = DbConnect::getInstance()->quote( $value );
		}
		$syntax = str_ireplace( $search, $replace, $syntax );

		return $syntax;

	}

}