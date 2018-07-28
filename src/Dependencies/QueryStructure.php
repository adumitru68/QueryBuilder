<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 4/22/2017 2:33 AM
 */

namespace Qpdb\QueryBuilder\Dependencies;


use Qpdb\QueryBuilder\Statements\QueryStatement;

class QueryStructure
{

	const TABLE = 'table';
	const EXPLAIN = 'explain';
	const STATEMENT = 'statement';
	const PRIORITY = 'priority';
	const FIELDS = 'fields';
	const SET_FIELDS = 'set_fields';
	const WHERE = 'where';
	const HAVING = 'having';
	const WHERE_INVERT = 'where_invert';
	const HAVING_INVERT = 'having_invert';
	const LIMIT = 'limit_rows';
	const ORDER_BY = 'order_by';
	const GROUP_BY = 'group_by';
	const COUNT = 'count';
	const COLUMN = 'column';
	const FIRST = 'first';
	const DISTINCT = 'distinct';
	const JOIN = 'join';
	const IGNORE = 'ignore';
	const MULTIPLE_ROWS = 'multiple_rows';
	const QUERY = 'query';
	const BIND_PARAMS = 'bind_params';
	const REPLACEMENT = 'replacement';
	const QUERY_TYPE = 'query_type';
	const QUERY_STRING = 'query_string';
	const QUERY_COMMENT = 'query_comment';
	const QUERY_IDENTIFIER = 'query_identifier';
	const WHERE_TRIGGER = 'where_trigger';
	const INSTANCE = 'instance';

	/**
	 *  Elements type
	 */

	const ELEMENT_TYPE_BOOLEAN = 'boolean';
	const ELEMENT_TYPE_INTEGER = 'integer';
	const ELEMENT_TYPE_DOUBLE = 'double';
	const ELEMENT_TYPE_STRING = 'string';
	const ELEMENT_TYPE_ARRAY = 'array';
	const ELEMENT_TYPE_OBJECT = 'object';
	const ELEMENT_TYPE_RESOURCE = 'resource';
	const ELEMENT_TYPE_NULL = 'NULL';
	const ELEMENT_TYPE_UNKNOWN = 'unknown type';

	/**
	 * @var array
	 */
	private static $usedInstanceIds = [];

	/**
	 * @var array
	 */
	private $syntaxEL = array();

	/**
	 * @var array
	 */
	private $typeEL = array();

	/**
	 * @var int
	 */
	private $counter = 0;

	/**
	 * @var array
	 */
	private $reserved = [
		'order',
		'select',
		'insert',
		'update',
		'alter',
		'delete'
	];


	/**
	 * QueryStructure constructor.
	 */
	public function __construct()
	{
		$this->syntaxEL = $this->init();

		foreach ( $this->syntaxEL as $name => $value )
			$this->typeEL[ $name ] = gettype( $value );

	}

	private function init()
	{
		return [
			self::TABLE => '',
			self::EXPLAIN => 0,
			self::STATEMENT => '',
			self::PRIORITY => '',
			self::FIELDS => '*',
			self::SET_FIELDS => array(),
			self::WHERE => array(),
			self::HAVING => array(),
			self::WHERE_INVERT => 0,
			self::HAVING_INVERT => 0,
			self::LIMIT => 0,
			self::ORDER_BY => array(),
			self::GROUP_BY => array(),
			self::COUNT => 0,
			self::COLUMN => '',
			self::FIRST => 0,
			self::DISTINCT => 0,
			self::JOIN => array(),
			self::IGNORE => 0,
			self::MULTIPLE_ROWS => 0,
			self::QUERY => '',
			self::BIND_PARAMS => array(),
			self::REPLACEMENT => 0,
			self::QUERY_TYPE => 0,
			self::QUERY_STRING => '',
			self::QUERY_COMMENT => '',
			self::QUERY_IDENTIFIER => 'DEFAULT',
			self::WHERE_TRIGGER => 1,
			self::INSTANCE => $this->makeStatementInstance()
		];
	}


	private function makeStatementInstance()
	{
		$instance = QueryHelper::random( 4 );
		while ( in_array( $instance, self::$usedInstanceIds ) ) {
			$instance = QueryHelper::random( 5 );
		}
		self::$usedInstanceIds[] = $instance;

		return $instance;
	}


	/**
	 * @return array
	 */
	public static function getUsedInstances()
	{
		return self::$usedInstanceIds;
	}


	/**
	 * @return array
	 */
	public function getAllElements()
	{
		return $this->syntaxEL;
	}

	/**
	 * @param $elements
	 */
	public function setAllElements( $elements )
	{
		$this->syntaxEL = $elements;
	}


	/**
	 * @param $name
	 * @param $value
	 * @return bool
	 * @throws QueryException
	 */
	public function setElement( $name, $value )
	{
		if ( !array_key_exists( $name, $this->syntaxEL ) )
			throw new QueryException( 'Invalid Query property', QueryException::QUERY_ERROR_ELEMENT_NOT_FOUND );

		if ( $name == self::TABLE && is_a( $value, QueryStatement::class ) )
			return true;

		if ( $this->typeEL[ $name ] === self::ELEMENT_TYPE_ARRAY )
			$this->syntaxEL[ $name ][] = $value;
		else
			$this->syntaxEL[ $name ] = $value;

		return true;
	}


	/**
	 * @param string $elementName
	 * @param $elementValue
	 * @throws QueryException
	 */
	public function replaceElement( $elementName, $elementValue )
	{
		if ( !array_key_exists( $elementName, $this->syntaxEL ) )
			throw new QueryException( 'Invalid Query property', QueryException::QUERY_ERROR_ELEMENT_NOT_FOUND );

		$this->syntaxEL[ $elementName ] = $elementValue;
	}


	/**
	 * @param string $name
	 * @return mixed
	 */
	public function getElement( $name )
	{
		return $this->syntaxEL[ $name ];
	}


	/**
	 * @param $name
	 * @param $value
	 */
	public function setParams( $name, $value )
	{
		$this->syntaxEL[ QueryStructure::BIND_PARAMS ][ $name ] = $value;
	}

	/**
	 * @param $expression
	 * @param array $params
	 * @param string $search
	 * @return string
	 */
	public function bindParamsExpression( $expression, array $params = [], $search = '?' )
	{
		if ( !count( $params ) )
			return $expression;

		if ( strpos( $expression, $search ) === false )
			return $expression;

		$params = array_slice( $params, 0, substr_count( $expression, $search ) );

		$i = 0;
		$arrayReturn = [];
		$expressionToArray = explode( $search, $expression );

		foreach ( $expressionToArray as $sub ) {
			$arrayReturn[] = $sub;
			$arrayReturn[] = array_key_exists( $i, $params ) ? $this->bindParam( 'exp', $params[ $i ] ) : '';
			$i++;
		}

		return implode( '', $arrayReturn );
	}

	/**
	 * @param $name
	 * @param $value
	 * @return string
	 */
	public function bindParam( $name, $value )
	{
		$pdoName = $this->index( $name );
		$this->syntaxEL[ QueryStructure::BIND_PARAMS ][ $pdoName ] = $value;

		return ':' . $pdoName;
	}

	public function prepare( $fieldName = '' )
	{
		return QueryHelper::addBacktick( $fieldName );
	}

	/**
	 * @param string $fieldName
	 * @return string
	 */
	public function index( $fieldName = '' )
	{
		$alphaNumericFieldName = preg_replace( "/[^a-zA-Z0-9_]+/", "", $fieldName );

		return $alphaNumericFieldName . '_' . $this->syntaxEL[ self::INSTANCE ] . '_' . ++$this->counter . 'i';
	}

}