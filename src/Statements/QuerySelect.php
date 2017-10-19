<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 4/22/2017 4:17 AM
 */

namespace Qpdb\QueryBuilder\Statements;


use Qpdb\QueryBuilder\DB\DbService;
use Qpdb\QueryBuilder\Dependencies\QueryHelper;
use Qpdb\QueryBuilder\Dependencies\QueryStructure;
use Qpdb\QueryBuilder\QueryBuild;
use Qpdb\QueryBuilder\Traits\DefaultPriority;
use Qpdb\QueryBuilder\Traits\Distinct;
use Qpdb\QueryBuilder\Traits\GroupBy;
use Qpdb\QueryBuilder\Traits\Having;
use Qpdb\QueryBuilder\Traits\HighPriority;
use Qpdb\QueryBuilder\Traits\Join;
use Qpdb\QueryBuilder\Traits\Limit;
use Qpdb\QueryBuilder\Traits\OrderBy;
use Qpdb\QueryBuilder\Traits\Replacement;
use Qpdb\QueryBuilder\Traits\SelectFields;
use Qpdb\QueryBuilder\Traits\Utilities;
use Qpdb\QueryBuilder\Traits\Where;
use Qpdb\QueryBuilder\Traits\WhereAndHavingBuilder;

class QuerySelect extends QueryStatement implements QueryStatementInterface
{

    use SelectFields, Limit, Distinct, Where, Having, WhereAndHavingBuilder, Replacement, OrderBy, GroupBy, Join, DefaultPriority, HighPriority, Utilities;

    /**
     * @var string
     */
    protected $statement = self::QUERY_STATEMENT_SELECT;


    /**
     * QuerySelect constructor.
     * @param QueryBuild $queryBuild
     * @param string|QueryStatement $table
     */
    public function __construct(QueryBuild $queryBuild, $table)
    {
        parent::__construct($queryBuild, $table);

        if (is_a($table, QuerySelect::class)) {

            /**
             * @var QuerySelect $table
             */
            $tableName = '( ' . $table->getSyntax() . ' )';
            $this->queryStructure->setElement(QueryStructure::TABLE, $tableName);

            $tableSelectParams = $table->getBindParams();
            foreach ($tableSelectParams as $key => $value)
                $this->queryStructure->setParams($key, $value);

        }
    }

    /**
     * @return $this
     */
    public function first()
    {
        $this->queryStructure->setElement(QueryStructure::FIRST, 1);

        return $this;
    }

    /**
     * @return $this
     */
    public function count()
    {
        $this->queryStructure->setElement(QueryStructure::COUNT, 1);

        return $this;
    }

    /**
     * @param $column
     * @return $this
     */
    public function column($column)
    {
        $column = QueryHelper::clearMultipleSpaces($column);
        $this->queryStructure->setElement(QueryStructure::COLUMN, $column);

        return $this;
    }


    /**
     * @param bool|int $replacement
     * @return mixed|string
     */
    public function getSyntax($replacement = self::REPLACEMENT_NONE)
    {

        if ($this->queryStructure->getElement(QueryStructure::COUNT)) {
            $this->queryStructure->setElement(QueryStructure::FIELDS, 'COUNT(*)');
            $this->queryStructure->setElement(QueryStructure::LIMIT, 1);
            $this->queryStructure->setElement(QueryStructure::DISTINCT, 0); //???
        }

        if ($this->queryStructure->getElement(QueryStructure::FIRST))
            $this->queryStructure->setElement(QueryStructure::LIMIT, 1);

        $syntax = array();

        /**
         *  Explain
         */
        $syntax[] = $this->getExplainSyntax();

        /**
         * SELECT statement
         */
        $syntax[] = $this->statement;

        /**
         * PRIORITY
         */
        $syntax[] = $this->queryStructure->getElement(QueryStructure::PRIORITY);

        /**
         * DISTINCT clause
         */
        $syntax[] = $this->getDistinctSyntax();

        /**
         * FIELDS
         */
        $syntax[] = $this->queryStructure->getElement(QueryStructure::FIELDS);

        /**
         * FROM table or queryStructure
         */
        $syntax[] = 'FROM ' . $this->queryStructure->getElement(QueryStructure::TABLE);

        /**
         * JOIN CLAUSES
         */
        $syntax[] = $this->getJoinSyntax();

        /**
         * WHERE clause
         */
        $syntax[] = $this->getWhereSyntax();

        /**
         * GROUP BY clause
         */
        $syntax[] = $this->getGroupBySyntax();

        /**
         * HAVING clause
         */
        $syntax[] = $this->getHavingSyntax();

        /**
         * ORDER BY clause
         */
        $syntax[] = $this->getOrderBySyntax();

        /**
         * LIMIT clause
         */
        $syntax[] = $this->getLimitSyntax();

        $syntax = implode(' ', $syntax);

        return $this->getSyntaxReplace($syntax, $replacement);

    }


    /**
     * @return array|int|mixed|null|string
     */
    public function execute()
    {

        switch (true) {
            case $this->queryStructure->getElement(QueryStructure::COUNT):
                return DbService::getInstance()->single($this->getSyntax(), $this->queryStructure->getElement(QueryStructure::BIND_PARAMS));
                break;
            case $this->queryStructure->getElement(QueryStructure::FIRST):
                if ($this->queryStructure->getElement(QueryStructure::COLUMN))
                    return DbService::getInstance()->single($this->getSyntax(), $this->queryStructure->getElement(QueryStructure::BIND_PARAMS));

                return DbService::getInstance()->row($this->getSyntax(), $this->queryStructure->getElement(QueryStructure::BIND_PARAMS));
                break;
            case $this->queryStructure->getElement(QueryStructure::COLUMN):
                return DbService::getInstance()->column($this->getSyntax(), $this->queryStructure->getElement(QueryStructure::BIND_PARAMS));
                break;
            default:
                return DbService::getInstance()->query($this->getSyntax(), $this->queryStructure->getElement(QueryStructure::BIND_PARAMS));
                break;
        }
    }

}