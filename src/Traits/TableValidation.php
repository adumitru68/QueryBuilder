<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 8/20/2017
 * Time: 12:36 AM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\DB\DbConfig;
use Qpdb\QueryBuilder\Dependencies\QueryException;
use Qpdb\QueryBuilder\Dependencies\QueryStructure;
use Qpdb\QueryBuilder\Statements\QuerySelect;
use Qpdb\QueryBuilder\Statements\QueryStatement;

trait TableValidation
{

	private function validateTable( $table )
	{
		switch ( gettype( $table ) ) {

			case QueryStructure::ELEMENT_TYPE_STRING:
				$table = $this->validateTableName( $table );
				break;
			case QueryStructure::ELEMENT_TYPE_OBJECT:
				$table = $this->validateTableSubQuery( $table );
				break;
			default:
				throw new QueryException( 'Invalid table type parameter: ' . gettype( $table ), QueryException::QUERY_ERROR_INVALID_TABLE_STATEMENT );

		}

		return $table;
	}

	/**
	 * @param $table
	 * @return mixed|string
	 * @throws QueryException
	 */
	private function validateTableName( $table )
	{
		$table = trim( $table );

		if ( '' === $table )
			throw new QueryException( 'Table name is empty string!', QueryException::QUERY_ERROR_INVALID_TABLE_STATEMENT );

		if ( DbConfig::getInstance()->useTablePrefix() )
			$table = str_ireplace( '::', DbConfig::getInstance()->getTablePrefix(), $table );

		return $table;
	}

	private function validateTableSubQuery( $table )
	{
		if ( $this->statement !== QueryStatement::QUERY_STATEMENT_SELECT )
			throw new QueryException( 'Invalid subQuery statement!', QueryException::QUERY_ERROR_INVALID_TABLE_STATEMENT );

		if ( !is_a( $table, QuerySelect::class ) )
			throw new QueryException( 'Invalid subQuery statement!', QueryException::QUERY_ERROR_INVALID_TABLE_STATEMENT );

		return $table;
	}


}