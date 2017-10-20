<?php
/**
 * Created by PhpStorm.
 * Author: Adrian Dumitru
 * Date: 6/3/2017 8:51 PM
 */

namespace Qpdb\QueryBuilder\DB;


class DbLog
{

	private static $instance;


	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var \DateTimeZone
	 */
	private $dateTimeZone;


	/**
	 * DbLog constructor.
	 */
	private function __construct()
	{
		$this->dateTimeZone = new \DateTimeZone( 'Europe/Bucharest' );
	}


	/**
	 * @param $query
	 * @param $duration
	 */
	public function writeQueryDuration( $query, $duration )
	{
		$backtrace = end( debug_backtrace() );
		$location = $backtrace[ 'file' ] . " Line: " . $backtrace[ 'line' ];

		$this->path = DbConfig::getInstance()->getLogPathQueryDuration(); //my comments
		$message = "Duration: " . round( $duration, 5 ) . "\r\n";
		$message .= "Location: $location\r\n";
		$message .= "Query: $query";
		$this->write( $message );
	}

	/**
	 * @param string $query
	 * @param \PDOException $e
	 */
	public function writeQueryErrors( $query, \PDOException $e )
	{
		$backtrace = end( $e->getTrace() );
		$location = $backtrace[ 'file' ] . " Line: " . $backtrace[ 'line' ];

		$this->path = DbConfig::getInstance()->getLogPathErrors(); //my comments
		$message = "Query: $query" . "\r\n";
		$message .= "Location: $location\r\n";
		$message .= "Error code : " . $e->getCode() . "\r\n";
		$message .= "" . $e->getMessage();

		$this->write( $message );
	}


	public function write( $message )
	{

		$date = new \DateTime();
		$date->setTimezone( $this->dateTimeZone );

		$log = $this->path . $date->format( 'Y-m-d' ) . ".txt";
		$time = $date->format( 'H:i:s' );

		$messageFormat = "[$time]\r\n$message\r\n\r\n";

		if ( is_dir( $this->path ) ) {
			if ( !file_exists( $log ) ) {
				$fh = fopen( $log, 'a+' );
				fwrite( $fh, $messageFormat );
				fclose( $fh );
			}
			else {
				$this->edit( $log, $messageFormat );
			}
		}
		else {
			if ( mkdir( $this->path, 0777 ) === true ) {
				$this->write( $message );
			}
		}
	}


	/**
	 * @param string $log
	 * @param  string $message
	 */
	private function edit( $log, $message )
	{
		file_put_contents( $log, $message, FILE_APPEND );
	}


	/**
	 * @return DbLog
	 */
	public static function getInstance()
	{
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}