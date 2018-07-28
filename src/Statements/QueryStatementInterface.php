<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 5/21/2017 9:29 AM
 */

namespace Qpdb\QueryBuilder\Statements;


interface QueryStatementInterface
{

	const REPLACEMENT_NONE = 0;
	const REPLACEMENT_VALUES = 1;

	/**
	 * @param int $replacement
	 * @return string
	 */
	public function getSyntax( $replacement = self::REPLACEMENT_NONE );

	public function execute();

	public function getBindParams();

}