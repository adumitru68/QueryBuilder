<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 6/26/2017 6:22 AM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryStructure;

trait SetFields
{

    use Objects;

    private $setValues;

    /**
     * @param $fieldName
     * @param $fieldValue
     * @return $this
     */
    public function setField($fieldName, $fieldValue)
    {
        $valuePdoString = $this->queryStructure->bindParam($fieldName, $fieldValue);
        $this->queryStructure->setElement(QueryStructure::SET_FIELDS, "$fieldName = $valuePdoString");

        return $this;
    }

    /**
     * @param string $expression
     * @return $this
     */
    public function setFieldByExpression($expression)
    {
        $this->queryStructure->setElement(QueryStructure::SET_FIELDS, $expression);

        return $this;
    }

    /**
     * Set fields by associative array ( fieldName => fieldValue )
     * @param array $updateFieldsArray
     * @return $this
     */
    public function setFieldsByArray(array $updateFieldsArray)
    {
        foreach ($updateFieldsArray as $fieldName => $fieldValue)
            $this->setField($fieldName, $fieldValue);

        return $this;
    }

    /**
     * @return string
     */
    private function getSettingFieldsSyntax()
    {
        if (!count($this->queryStructure->getElement(QueryStructure::SET_FIELDS)))
            return '';

        return 'SET ' . implode(', ', $this->queryStructure->getElement(QueryStructure::SET_FIELDS));
    }

}