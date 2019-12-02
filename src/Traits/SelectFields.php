<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 8/15/2017
 * Time: 12:15 PM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\Common\Helpers\Arrays;
use Qpdb\Common\Helpers\Strings;
use Qpdb\QueryBuilder\Dependencies\QueryException;
use Qpdb\QueryBuilder\Dependencies\QueryStructure;

/**
 * Trait SelectFields
 * @package Qpdb\QueryBuilder\Traits
 * @property QueryStructure $queryStructure
 */
trait SelectFields
{


	public function fields( ...$fields ) {
		$fields = Arrays::flattenValues( $this->normalizeFields( $fields ), true );
		foreach ( $fields as $field ) {
			$field = Strings::removeMultipleSpace( $field, true );
			if ( '' === $field ) {
				continue;
			}
			if ( str_word_count( $field ) === 1 ) {
				$this->queryStructure->setElement( QueryStructure::FIELDS, $this->queryStructure->prepare( $field ) );
			} else {
				$fieldParts = explode( ' ', $field );
				$fieldName = $this->queryStructure->prepare( $fieldParts[ 0 ] );
				array_shift( $fieldParts );
				$fieldAlias = $this->queryStructure->prepare( implode( ' ', $fieldParts ) );
				$this->fieldExpression( $fieldName, $fieldAlias );
			}
		}

		return $this;
	}

	/**
	 * @param             $field
	 * @param null|string $alias
	 * @return $this
	 * @throws QueryException
	 */
	public function fieldExpression( $field, $alias = null ) {
		$field = trim( $field );
		if ( '' !== $alias ) {
			$field .= ' ' . $alias;
		}
		$this->queryStructure->setElement( QueryStructure::FIELDS, $field );

		return $this;
	}

	private function normalizeFields( $fields ) {
		$array = Arrays::flattenValues( $fields );
		$array = (array)$array;
		$return = array();
		array_walk_recursive( $array, function( $a ) use ( &$return ) {
			$a = explode( ',', $a );
			$return[] = $a;
		} );

		return $return;
	}

	/**
	 * @return string
	 */
	private function getSelectFieldsSyntax() {
		if ( empty( $this->queryStructure->getElement( QueryStructure::FIELDS ) ) ) {
			return '*';
		}

		return implode( ', ', (array)$this->queryStructure->getElement( QueryStructure::FIELDS ));
	}

}