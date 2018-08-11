<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 8/15/2017
 * Time: 12:15 PM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryException;
use Qpdb\QueryBuilder\Dependencies\QueryHelper;
use Qpdb\QueryBuilder\Dependencies\QueryStructure;

/**
 * Trait SelectFields
 * @package Qpdb\QueryBuilder\Traits
 * @property QueryStructure $queryStructure
 */
trait SelectFields
{

	/**
	 * @param string|array $fields
	 * @return $this
	 * @throws QueryException
	 */
	public function fields( $fields )
	{

		switch ( gettype( $fields ) ) {
			case QueryStructure::ELEMENT_TYPE_ARRAY:

				$fields = $this->prepareArrayFields( $fields );

				if ( count( $fields ) )
					$this->queryStructure->setElement( QueryStructure::FIELDS, implode( ', ', $fields ) );
				else
					$this->queryStructure->setElement( QueryStructure::FIELDS, '*' );
				break;

			case QueryStructure::ELEMENT_TYPE_STRING:

				$fields = trim( $fields );
				if ( '' !== $fields ) {
					$fields = explode( ',', $fields );
					$fields = $this->prepareArrayFields( $fields );
					$this->queryStructure->setElement( QueryStructure::FIELDS, implode( ', ', $fields ) );
				}
				else
					$this->queryStructure->setElement( QueryStructure::FIELDS, '*' );
				break;

			default:
				throw new QueryException( 'Invalid fields parameter type', QueryException::QUERY_ERROR_WHERE_INVALID_PARAM_ARRAY );

		}

		return $this;
	}

	public function fieldsByExpression( $expression )
	{
		$expression = trim( $expression );
		$this->queryStructure->setElement( QueryStructure::FIELDS, $expression );

		return $this;
	}



	/**
	 * @param array $fieldsArray
	 * @return array
	 * @throws QueryException
	 */
	private function prepareArrayFields( $fieldsArray = array() )
	{
		$prepareArray = [];

		foreach ( $fieldsArray as $field ) {

			switch ( gettype( $field ) ) {
				case QueryStructure::ELEMENT_TYPE_STRING:
					$prepareArray[] = $this->queryStructure->prepare( $field );
					break;
				case QueryStructure::ELEMENT_TYPE_ARRAY:
					$prepareArray[] = $this->getFieldByArray( $field );
					break;
				default:
					throw new QueryException('Invalid field description');
			}
		}

		return $prepareArray;
	}


	/**
	 * @param array $fieldArray
	 * @return string
	 * @throws QueryException
	 */
	private function getFieldByArray( array $fieldArray )
	{

		if ( !in_array( count( $fieldArray ), [ 1, 2 ] ) ) {
			throw new QueryException( 'Invalid descriptive array from field.' );
		}

		if ( count( $fieldArray ) === 1 ) {
			return $this->queryStructure->prepare( trim( $fieldArray[ 0 ] ) );
		}
		else {
			return $this->queryStructure->prepare( trim( $fieldArray[ 0 ] ) ) . ' ' . $this->queryStructure->prepare( trim( $fieldArray[ 1 ] ) );
		}

	}

}