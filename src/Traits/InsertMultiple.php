<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 7/9/2017
 * Time: 10:41 AM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryException;
use Qpdb\QueryBuilder\Dependencies\QueryHelper;
use Qpdb\QueryBuilder\Dependencies\QueryStructure;
use Qpdb\QueryBuilder\Statements\QuerySelect;

trait InsertMultiple
{

    use Objects;


    /**
     * @param $fieldsList
     * @param array $rowsForInsert
     * @return $this
     */
    public function fromArray($fieldsList, array $rowsForInsert)
    {
        $this->setFieldsList($fieldsList);

        foreach ($rowsForInsert as $row)
            $this->addSingleRow($row);

        return $this;
    }


    /**
     * @param $fieldsList
     * @param QuerySelect $query
     * @return $this
     */
    public function fromQuerySelect($fieldsList, QuerySelect $query)
    {
        $this->setFieldsList($fieldsList);

        foreach ($query->getBindParams() as $key => $value)
            $this->queryStructure->setParams($key, $value);

        $this->queryStructure->replaceElement(QueryStructure::SET_FIELDS, $query);

        return $this;
    }


    /**
     * @param string|array $fieldList
     */
    private function setFieldsList($fieldList)
    {
        if (!is_array($fieldList))
            $fieldList = QueryHelper::explode($fieldList);

        $this->queryStructure->replaceElement(QueryStructure::FIELDS, $fieldList);

    }

    /**
     * @param array $rowValues
     * @return $this
     * @throws QueryException
     */
    private function addSingleRow(array $rowValues)
    {

        if ($this->getNumberOfFields() !== count($rowValues))
            throw new QueryException('Ivalid number of fields.', QueryException::QUERY_ERROR_INVALID_FIELDS_COUNT);

        $pdoRowValues = array();

        foreach ($rowValues as $value)
            $pdoRowValues[] = $this->queryStructure->bindParam('value', $value);

        $pdoRowValuesString = '( ' . QueryHelper::implode($pdoRowValues, ', ') . ' )';

        $this->queryStructure->setElement(QueryStructure::SET_FIELDS, $pdoRowValuesString);

        return $this;
    }

    /**
     * @return string
     */
    private function getInsertMultipleRowsSyntax()
    {
        if (is_a($this->queryStructure->getElement(QueryStructure::SET_FIELDS), QuerySelect::class))
            return $this->getSyntaxWithSelect();

        $fields = '( ' . QueryHelper::implode($this->queryStructure->getElement(QueryStructure::FIELDS), ', ') . ' )';
        $values = QueryHelper::implode($this->queryStructure->getElement(QueryStructure::SET_FIELDS), ', ');

        return $fields . ' VALUES ' . $values;
    }


    /**
     * @return string
     */
    private function getSyntaxWithSelect()
    {
        $fields = '( ' . QueryHelper::implode($this->queryStructure->getElement(QueryStructure::FIELDS), ', ') . ' )';

        return $fields . ' ' . $this->queryStructure->getElement(QueryStructure::SET_FIELDS)->getSyntax();
    }

    /**
     * @return int
     */
    private function getNumberOfFields()
    {
        return count($this->queryStructure->getElement(QueryStructure::FIELDS));
    }


}