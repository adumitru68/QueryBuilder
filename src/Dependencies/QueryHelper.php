<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 4/23/2017 12:02 AM
 */

namespace Qpdb\QueryBuilder\Dependencies;


class QueryHelper
{

	/**
	 * @param string $string
	 * @return mixed|string
	 */
	public static function clearMultipleSpaces( $string = '' )
	{
		$string = preg_replace( '/\s+/', ' ', $string );
		$string = trim( $string );

		return $string;
	}

	/**
	 * @param $val
	 * @return bool
	 */
	public static function isInteger( $val )
	{
		$val = trim( $val );

		return is_numeric( $val ) && floor( $val ) == $val;
	}

	/**
	 * @param $val
	 * @return bool
	 * @i
	 */
	public static function isDecimal( $val )
	{
		$val = trim( $val );

		return is_numeric( $val ) && floor( $val ) != $val;
	}

	public static function isValidOrderBy( $order_by_parameter )
	{

		$columns = array( 'first_name', 'last_name', 'zip', 'created_at' );

		$parts = preg_split( "/[\s,]+/", $order_by_parameter );

		foreach ( $parts as $part ) {
			$subparts = preg_split( "/\s+/", $part );

			if ( count( $subparts ) < 0 || count( $subparts ) > 2 ) {
				// Too many or too few parts.
				return false;
			}

			if ( !in_array( $subparts[ 0 ], $columns ) ) {
				// Column name is invalid.
				return false;
			}

			if ( count( $subparts ) == 2 && !in_array( strtoupper( $subparts[ 1 ] ), array( 'ASC', 'DESC' ) ) ) {
				// ASC or DESC is invalid
				return false;
			}
		}

		return true;
	}


	/**
	 * @param $string
	 * @param string $delimiter
	 * @return array
	 */
	public static function explode( $string, $delimiter = ',' )
	{
		$brutArray = explode( $delimiter, $string );
		$newArray = array();
		foreach ( $brutArray as $value ) {
			$value = trim( $value );
			if ( '' !== $value )
				$newArray[] = $value;
		}

		return $newArray;
	}

	public static function alphaNum( $string )
	{
		$string = preg_replace( "/[^a-zA-Z0-9 _,]+/", "", $string );

		return self::clearMultipleSpaces( $string );
	}

	/**
	 * @param array $array
	 * @param string $delimiter
	 * @return string
	 */
	public static function implode( array $array, $delimiter = ',' )
	{
		$string = implode( $delimiter, $array );
		$string = trim( $string );
		$string = trim( $string, trim( $delimiter ) );
		$string = trim( $string );

		return $string;
	}

	/**
	 * @param $string
	 * @return mixed|string
	 */
	public static function clearQuotes( $string )
	{
		$search = array( '"', "'" );
		$replace = '';
		$string = str_replace( $search, $replace, $string );

		return self::clearMultipleSpaces( $string );
	}

	/**
	 * @param int $length
	 * @return string
	 */
	public static function random( $length = 5 )
	{
		$characters = 'abcdefghijklmnopqrstuvwxyz';
		$charactersLength = strlen( $characters );
		$randomString = '';
		for ( $i = 0; $i < $length; $i++ )
			$randomString .= $characters[ rand( 0, $charactersLength - 1 ) ];

		return str_shuffle( $randomString );
	}

	public static function limitString( $rowCount, $offset = null )
	{
		$rowCount = intval( $rowCount );
		if ( is_null( $offset ) )
			return $rowCount;
		$offset = intval( $offset );

		return "$offset, $rowCount";
	}

}