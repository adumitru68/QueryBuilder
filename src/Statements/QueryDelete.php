<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 6/27/2017
 * Time: 11:18 AM
 */

namespace Qpdb\QueryBuilder\Statements;


use Qpdb\QueryBuilder\DB\DbService;
use Qpdb\QueryBuilder\Dependencies\QueryException;
use Qpdb\QueryBuilder\Dependencies\QueryStructure;
use Qpdb\QueryBuilder\QueryBuild;
use Qpdb\QueryBuilder\Traits\DefaultPriority;
use Qpdb\QueryBuilder\Traits\Ignore;
use Qpdb\QueryBuilder\Traits\Limit;
use Qpdb\QueryBuilder\Traits\LowPriority;
use Qpdb\QueryBuilder\Traits\OrderBy;
use Qpdb\QueryBuilder\Traits\Replacement;
use Qpdb\QueryBuilder\Traits\SetFields;
use Qpdb\QueryBuilder\Traits\Utilities;
use Qpdb\QueryBuilder\Traits\Where;
use Qpdb\QueryBuilder\Traits\WhereAndHavingBuilder;

class QueryDelete extends QueryStatement implements QueryStatementInterface
{

	use Limit, Where, WhereAndHavingBuilder, Replacement, OrderBy, SetFields, Ignore, DefaultPriority, LowPriority, Utilities;

	/**
	 * @var string
	 */
	protected $statement = self::QUERY_STATEMENT_DELETE;


	/**
	 * QueryDelete constructor.
	 * @param QueryBuild $queryBuild
	 * @param string $table
	 */
	public function __construct( QueryBuild $queryBuild, $table = null )
	{
		parent::__construct( $queryBuild, $table );
	}

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
		 * TABLE update
		 */
		$syntax[] = 'FROM ' . $this->queryStructure->getElement( QueryStructure::TABLE );

		/**
		 * WHERE clause
		 */
		$syntax[] = $this->getWhereSyntax();

		/**
		 * ORDER BY clause
		 */
		$syntax[] = $this->getOrderBySyntax();

		/**
		 * LIMIT clause
		 */
		$syntax[] = $this->getLimitSyntax();

		$syntax = implode( ' ', $syntax );

		return $this->getSyntaxReplace( $syntax, $replacement );
	}

	/**
	 * @return array|int|null
	 * @throws QueryException
	 */
	public function execute()
	{

		if ( $this->queryStructure->getElement( ( QueryStructure::WHERE_TRIGGER ) ) && !count( $this->queryStructure->getElement( QueryStructure::WHERE ) ) )
			throw new QueryException( 'Where or Having clause is required for this statement!', QueryException::QUERY_ERROR_DELETE_NOT_FILTER );

		return DbService::getInstance()->query( $this->getSyntax(), $this->queryStructure->getElement( QueryStructure::BIND_PARAMS ) );

	}


}