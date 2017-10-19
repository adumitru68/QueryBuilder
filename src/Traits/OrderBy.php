<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 4/30/2017 8:26 PM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryException;
use Qpdb\QueryBuilder\Dependencies\QueryHelper;
use Qpdb\QueryBuilder\Dependencies\QueryStructure;

trait OrderBy
{

    use Objects;


    /**
     * @param $column
     * @param array $allowedColumns
     * @return $this
     * @throws QueryException
     */
    public function orderBy($column, array $allowedColumns = [])
    {
        $column = trim($column);

        if (!$this->validateColumn($column, $allowedColumns))
            throw new QueryException('Invalid column name in ORDER BY clause', QueryException::QUERY_ERROR_INVALID_COLUMN_NAME);

        $this->queryStructure->setElement(QueryStructure::ORDER_BY, $column);

        return $this;
    }


    /**
     * @param $column
     * @param array $allowedColumns
     * @return $this
     * @throws QueryException
     */
    public function orderByDesc($column, array $allowedColumns = [])
    {
        $column = trim($column);

        if (!$this->validateColumn($column, $allowedColumns))
            throw new QueryException('Invalid column name in ORDER BY clause', QueryException::QUERY_ERROR_INVALID_COLUMN_NAME);

        $this->queryStructure->setElement(QueryStructure::ORDER_BY, $column . ' DESC');

        return $this;
    }


    /**
     * @param $expression
     * @return $this
     */
    public function orderByExpression($expression)
    {
        $this->queryStructure->setElement(QueryStructure::ORDER_BY, $expression);

        return $this;
    }


    /**
     * @return string
     */
    private function getOrderBySyntax()
    {
        if (count($this->queryStructure->getElement(QueryStructure::ORDER_BY)))
            return 'ORDER BY ' . QueryHelper::implode($this->queryStructure->getElement(QueryStructure::ORDER_BY), ', ');

        return '';
    }

}