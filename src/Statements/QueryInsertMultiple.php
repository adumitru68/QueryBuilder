<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 7/24/2017
 * Time: 6:34 PM
 */

namespace Qpdb\QueryBuilder\Statements;


use Qpdb\QueryBuilder\DB\DbService;
use Qpdb\QueryBuilder\Dependencies\QueryStructure;
use Qpdb\QueryBuilder\QueryBuild;
use Qpdb\QueryBuilder\Traits\DefaultPriority;
use Qpdb\QueryBuilder\Traits\HighPriority;
use Qpdb\QueryBuilder\Traits\Ignore;
use Qpdb\QueryBuilder\Traits\InsertMultiple;
use Qpdb\QueryBuilder\Traits\LowPriority;
use Qpdb\QueryBuilder\Traits\Replacement;
use Qpdb\QueryBuilder\Traits\Utilities;

class QueryInsertMultiple extends QueryStatement implements QueryStatementInterface
{

	use InsertMultiple, Replacement, Ignore, DefaultPriority, LowPriority, HighPriority, Utilities;

	/**
	 * @var string
	 */
	protected $statement = self::QUERY_STATEMENT_INSERT;


	/**
	 * QueryInsert constructor.
	 * @param QueryBuild $queryBuild
	 * @param string $table
	 */
	public function __construct( QueryBuild $queryBuild, $table = null )
	{
		parent::__construct( $queryBuild, $table );
		$this->queryStructure->setElement( QueryStructure::FIELDS, array() );
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
		$syntax[] = $this->getInsertMultipleRowsSyntax();

		$syntax = implode( ' ', $syntax );

		return $this->getSyntaxReplace( $syntax, $replacement );

	}

	public function execute()
	{
		return DbService::getInstance()->query(
			$this->getSyntax(),
			$this->queryStructure->getElement( QueryStructure::BIND_PARAMS )
		);
	}
}