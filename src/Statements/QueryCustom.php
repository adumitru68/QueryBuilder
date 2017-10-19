<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 7/27/2017
 * Time: 10:39 PM
 */

namespace Qpdb\QueryBuilder\Statements;


use Qpdb\QueryBuilder\DB\DbService;
use Qpdb\QueryBuilder\Dependencies\QueryStructure;
use Qpdb\QueryBuilder\QueryBuild;

class QueryCustom implements QueryStatementInterface
{


	/**
	 * @var string
	 */
	protected $statement = QueryStatement::QUERY_STATEMENT_CUSTOM;

	/**
	 * @var QueryBuild
	 */
	protected $queryBuild;

	/**
	 * @var QueryStructure
	 */
	protected $queryStructure;

	/**
	 * @var array
	 */
	protected $usedInstanceIds = [];

	/**
	 * @var string
	 */
	protected $tablePrefix;


	/**
	 * QueryCustom constructor.
	 * @param QueryBuild $queryBuild
	 * @param string $query
	 */
	public function __construct( QueryBuild $queryBuild, $query = '' )
	{
		$this->queryBuild = $queryBuild;
		$this->queryStructure = new QueryStructure();
		$this->queryStructure->setElement( QueryStructure::STATEMENT, $this->statement );
		$this->queryStructure->setElement( QueryStructure::QUERY_TYPE, $this->queryBuild->getType() );
		$this->queryStructure->setElement( QueryStructure::QUERY_STRING, $query );
	}

	/**
	 * @param array $params
	 * @return $this
	 */
	public function withBindParams( array $params = [] )
	{
		$this->queryStructure->replaceElement( QueryStructure::BIND_PARAMS, $params );

		return $this;
	}

	/**
	 * @param bool|int $replacement
	 * @return mixed
	 */
	public function getSyntax( $replacement = self::REPLACEMENT_NONE )
	{
		return $this->queryStructure->getElement( QueryStructure::QUERY_STRING );
	}

	/**
	 * @return array
	 */
	public function getBindParams()
	{
		return $this->queryStructure->getElement( QueryStructure::BIND_PARAMS );
	}

	/**
	 * @return array|int|null
	 */
	public function execute()
	{
		return DbService::getInstance()->query( $this->queryStructure->getElement( QueryStructure::QUERY_STRING ), $this->queryStructure->getElement( QueryStructure::BIND_PARAMS ) );
	}


}