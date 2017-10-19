<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 8/15/2017
 * Time: 12:15 PM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryException;
use Qpdb\QueryBuilder\Dependencies\QueryStructure;

trait SelectFields
{
	use Objects;


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
				if ( '' !== $fields )
					$this->queryStructure->setElement( QueryStructure::FIELDS, $fields );
				else
					$this->queryStructure->setElement( QueryStructure::FIELDS, '*' );
				break;

			default:
				throw new QueryException( 'Invalid fields parameter type', QueryException::QUERY_ERROR_WHERE_INVALID_PARAM_ARRAY );

		}

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
			if ( gettype( $field ) !== QueryStructure::ELEMENT_TYPE_STRING )
				throw new QueryException( 'Invalid select field type!', QueryException::QUERY_ERROR_SELECT_INVALID_FIELD );

			if ( '' !== trim( $field ) )
				$prepareArray[] = trim( $field );
		}

		return $prepareArray;
	}

}