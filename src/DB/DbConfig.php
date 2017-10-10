<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 5/27/2017 1:07 PM
 */

namespace Qpdb\QueryBuilder\DB;


class DbConfig
{

	private static $instance;

	/**
	 * @var array
	 */
	private $dbConfig;

	/**
	 * @var bool
	 */
	private $replicationEnable;

	/**
	 * @var array
	 */
	private $masterDataConnect;

	/**
	 * @var array
	 */
	private $slaveDataConnect;

	/**
	 * @var bool
	 */
	private $enableLogErrors = false;

	/**
	 * @var bool
	 */
	private $enableLogQueryDuration = false;

	/**
	 * @var string
	 */
	private $logPathErrors;

	/**
	 * @var string
	 */
	private $logPathQueryDuration;


	/**
	 * DbConfig constructor.
	 */
	private function __construct()
	{
		$vendorCfg = __DIR__ . '/../../../../../vendor-cfg/qpdb_db_config.php';
		if(file_exists($vendorCfg))
			$this->dbConfig = require $vendorCfg;
		else
			$this->dbConfig = require __DIR__ . '/../../config/qpdb_db_config.php';

		$this->buildConfig();
	}

	/**
	 * @param string $fileConfig
	 * @return $this
	 */
	public function withFileConfig( $fileConfig )
	{
		$this->dbConfig = require $fileConfig;
		$this->buildConfig();

		return $this;
	}

	/**
	 * @return bool
	 */
	public function getReplicationEnable()
	{
		return $this->replicationEnable;
	}

	/**
	 * @return array
	 */
	public function getMasterDataConnect()
	{
		return $this->masterDataConnect;
	}

	/**
	 * @return array
	 */
	public function getSlaveDataConnect()
	{
		return $this->slaveDataConnect;
	}

	/**
	 * @return array
	 */
	public function getLogConfig()
	{
		return $this->dbConfig['db_log'];
	}

	/**
	 * @return bool
	 */
	public function isEnableLogErrors()
	{
		return $this->enableLogErrors;
	}

	/**
	 * @return bool
	 */
	public function isEnableLogQueryDuration()
	{
		return $this->enableLogQueryDuration;
	}

	/**
	 * @return string
	 */
	public function getLogPathErrors()
	{
		return $this->logPathErrors;
	}

	/**
	 * @return string
	 */
	public function getLogPathQueryDuration()
	{
		return $this->logPathQueryDuration;
	}

	public function useTablePrefix()
	{
		if ( !empty( $this->dbConfig['use_table_prefix'] ) )
			return $this->dbConfig['use_table_prefix'];

		return false;
	}

	public function getTablePrefix()
	{
		return $this->dbConfig['table_prefix'];
	}


	private function buildConfig()
	{
		$this->replicationEnable = $this->dbConfig['replicationEnable'];
		$this->readMasterDataConnect();
		$this->readSlaveDataConnect();
		$this->configLogger();
	}

	private function configLogger()
	{
		$this->enableLogErrors = $this->dbConfig['db_log']['enable_log_errors'];
		$this->enableLogQueryDuration = $this->dbConfig['db_log']['enable_log_query_duration'];
		$this->logPathErrors = $this->dbConfig['db_log']['log_path_errors'];
		$this->logPathQueryDuration = $this->dbConfig['db_log']['log_path_query_duration'];

	}

	/**
	 * @return bool
	 * @throws DbException
	 */
	private function readMasterDataConnect()
	{

		if ( !isset( $this->dbConfig['master_data_connect'][0] ) )
			throw new DbException( 'Master data connect is missing', DbException::DB_ERROR_MASTER_DATA_CONNECTION_MISSING );

		$dataConnection = $this->dbConfig['master_data_connect'];

		if ( !$this->replicationEnable || count( $dataConnection ) == 1 ) {
			$this->masterDataConnect = $dataConnection[0];

			return true;
		}

		shuffle( $dataConnection );
		$this->masterDataConnect = $dataConnection[0];

		return true;
	}


	/**
	 * @return bool
	 */
	private function readSlaveDataConnect()
	{

		if ( !isset( $this->dbConfig['slave_data_connect'][0] ) ) {
			$this->slaveDataConnect = $this->masterDataConnect;

			return true;
		}

		$dataConnection = $this->dbConfig['slave_data_connect'];

		shuffle( $dataConnection );
		$this->slaveDataConnect = $dataConnection[0];

		return true;
	}

	/**
	 * @return DbConfig
	 */
	public static function getInstance()
	{
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


}