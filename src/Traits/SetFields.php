<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 6/26/2017 6:22 AM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryStructure;

/**
 * Trait SetFields
 * @package Qpdb\QueryBuilder\Traits
 * @property QueryStructure $queryStructure
 */
trait SetFields
{

	private $setValues;

	/**
	 * @param $fieldName
	 * @param $fieldValue
	 * @return $this
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function setField( $fieldName, $fieldValue )
	{
		$fieldName = $this->queryStructure->prepare($fieldName);
		$valuePdoString = $this->queryStructure->bindParam( $fieldName, $fieldValue );
		$this->queryStructure->setElement( QueryStructure::SET_FIELDS, $this->queryStructure->prepare($fieldName) . " = $valuePdoString" );

		return $this;
	}

	/**
	 * @param string $expression
	 * @return $this
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function setFieldByExpression( $expression )
	{
		$this->queryStructure->setElement( QueryStructure::SET_FIELDS, $expression );

		return $this;
	}

	/**
	 * Set fields by associative array ( fieldName => fieldValue )
	 * @param array $updateFieldsArray
	 * @return $this
	 * @throws \Qpdb\QueryBuilder\Dependencies\QueryException
	 */
	public function setFieldsByArray( array $updateFieldsArray )
	{
		foreach ( $updateFieldsArray as $fieldName => $fieldValue )
			$this->setField( $fieldName, $fieldValue );

		return $this;
	}

	/**
	 * @return string
	 */
	private function getSettingFieldsSyntax()
	{
		if ( !count( $this->queryStructure->getElement( QueryStructure::SET_FIELDS ) ) )
			return '';

		return 'SET ' . implode( ', ', $this->queryStructure->getElement( QueryStructure::SET_FIELDS ) );
	}

}