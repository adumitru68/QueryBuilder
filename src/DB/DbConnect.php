<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 5/6/2017 3:08 PM
 */

namespace Qpdb\QueryBuilder\DB;


class DbConnect
{

	private static $instance;

	/**
	 * @var \PDO
	 */
	private $pdoMaster;

	/**
	 * @var \PDO
	 */
	private $pdoSlave;

	/**
	 * @var DbConfig
	 */
	private $config;


	/**
	 * DbConnect constructor.
	 */
	private function __construct()
	{
		$this->config = DbConfig::getInstance();
	}

	/**
	 * @return \PDO
	 */
	public function getMasterConnection()
	{
		if ( !is_a( $this->pdoMaster, \PDO::class ) )
			$this->pdoMaster = $this->connect( $this->config->getMasterDataConnect() );

		return $this->pdoMaster;
	}

	/**
	 * @return \PDO
	 */
	public function getSlaveConnection()
	{
		if ( !$this->config->getReplicationEnable() )
			return $this->getMasterConnection();

		if ( !is_a( $this->pdoSlave, \PDO::class ) )
			$this->pdoSlave = $this->connect( $this->config->getSlaveDataConnect() );

		return $this->pdoSlave;
	}

	/**
	 * @param $statement
	 * @return \PDO
	 */
	public function getConnection( $statement )
	{
		$statement = trim( strtolower( $statement ) );

		if ( $statement === DbService::QUERY_TYPE_SELECT )
			return $this->getSlaveConnection();

		return $this->getMasterConnection();
	}

	/**
	 * @param $string
	 * @return string
	 */
	public function quote( $string )
	{
		return $this->getMasterConnection()->quote( $string );
	}

	/**
	 * @return string
	 */
	public function lastInsertId()
	{
		return $this->getMasterConnection()->lastInsertId();
	}

	/**
	 * @param $dataConnect
	 * @return \PDO
	 */
	private function connect( $dataConnect )
	{
		$dsn = 'mysql:dbname=' . $dataConnect["dbname"] . ';host=' . $dataConnect["host"] . '';
		try {

			$pdo = new \PDO( $dsn, $dataConnect["user"], $dataConnect["password"],
				array( \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8" )
			);

			$pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
			$pdo->setAttribute( \PDO::ATTR_EMULATE_PREPARES, false );
		} catch ( \PDOException $e ) {
			if ( DbConfig::getInstance()->isEnableLogErrors() ) {
				DbLog::getInstance()->writeQueryErros( 'Connection fail!', $e->getCode(), $e->getMessage() );
			}
			throw new \PDOException( $e->getMessage(), $e->getCode() );
		}

		return $pdo;
	}


	/**
	 * @return DbConnect
	 */
	public static function getInstance()
	{
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}