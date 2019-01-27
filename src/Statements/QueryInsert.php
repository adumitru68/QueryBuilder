<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 6/27/2017
 * Time: 1:51 PM
 */

namespace Qpdb\QueryBuilder\Statements;


use Qpdb\QueryBuilder\DB\DbService;
use Qpdb\QueryBuilder\Dependencies\QueryStructure;
use Qpdb\QueryBuilder\QueryBuild;
use Qpdb\QueryBuilder\Traits\DefaultPriority;
use Qpdb\QueryBuilder\Traits\HighPriority;
use Qpdb\QueryBuilder\Traits\Ignore;
use Qpdb\QueryBuilder\Traits\LowPriority;
use Qpdb\QueryBuilder\Traits\Replacement;
use Qpdb\QueryBuilder\Traits\SetFields;
use Qpdb\QueryBuilder\Traits\Utilities;

class QueryInsert extends QueryStatement implements QueryStatementInterface
{

	use Replacement, SetFields, Ignore, DefaultPriority, LowPriority, HighPriority, Utilities;

	/**
	 * @var string
	 */
	protected $statement = self::QUERY_STATEMENT_INSERT;


	/**
	 * QueryInsert constructor.
	 * @param QueryBuild $queryBuild
	 * @param string $table
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function __construct( QueryBuild $queryBuild, $table = null )
	{
		parent::__construct( $queryBuild, $table );
	}

	/**
	 * @return QueryInsertMultiple
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function multiple()
	{
		return new QueryInsertMultiple( $this->queryBuild, $this->queryStructure->getElement( QueryStructure::TABLE ) );
	}

	/**
	 * @param bool|int $replacement
	 * @return mixed|string
	 */
	public function getSyntax( $replacement = self::REPLACEMENT_NONE )
	{
		$syntax = array();

		/**
		 *  Explain
		 */
		$syntax[] = $this->getExplainSyntax();

		/**
		 * UPDATE statement
		 */
		$syntax[] = $this->statement;

		/**
		 * PRIORITY
		 */
		$syntax[] = $this->queryStructure->getElement( QueryStructure::PRIORITY );

		/**
		 * IGNORE clause
		 */
		$syntax[] = $this->queryStructure->getElement( QueryStructure::IGNORE ) ? 'IGNORE' : '';

		/**
		 * INTO table
		 */
		$syntax[] = 'INTO ' . $this->queryStructure->getElement( QueryStructure::TABLE );

		/**
		 * FIELDS update
		 */
		$syntax[] = $this->getSettingFieldsSyntax();

		$syntax = implode( ' ', $syntax );

		return $this->getSyntaxReplace( $syntax, $replacement );

	}


	public function execute()
	{
		return DbService::getInstance()->query( $this->getSyntax(), $this->queryStructure->getElement( QueryStructure::BIND_PARAMS ) );
	}


}