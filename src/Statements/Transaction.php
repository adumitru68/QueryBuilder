<?php
/**
 * Created by PhpStorm.
 * User: Adi
 * Date: 8/25/2018
 * Time: 12:28 PM
 */

namespace Qpdb\QueryBuilder\Statements;


use Qpdb\Common\Helpers\Arrays;
use Qpdb\PdoWrapper\PdoWrapperService;

class Transaction implements QueryStatementInterface
{
	/**
	 * @var QueryStatementInterface[];
	 */
	private $transaction;

	/**
	 * @param QueryStatementInterface ...$queries
	 * @return $this
	 */
	public function withQuery( QueryStatementInterface ...$queries ) {
		$queries = Arrays::flattenValues( $queries );
		foreach ( $queries as $query ) {
			$this->transaction = $query;
		}

		return $this;
	}

	/**
	 * @param int $replacement
	 * @return array
	 */
	public function getSyntax( $replacement = self::REPLACEMENT_NONE ) {
		$queries = [];
		foreach ( $this->transaction as $queryStatement ) {
			$queries[] = $queryStatement->getSyntax( $replacement );
		}

		return $queries;
	}

	/**
	 * @param \Closure $function
	 * @param array    $params
	 * @return mixed|null
	 */
	public function executeFunction( \Closure $function, array $params = [] ) {
		return PdoWrapperService::getInstance()->transaction( $function, $params );
	}

	/**
	 * @return mixed|null
	 */
	public function execute() {
		$transaction = (array)$this->transaction;

		return PdoWrapperService::getInstance()->transaction(
			function() use ( $transaction ) {
				$i = 0;
				foreach ( $transaction as $queryStatement ) {
					$queryStatement->execute();
					$i++;
				}

				return $i;
			}
		);
	}

	/**
	 * @return array
	 */
	public function getBindParams() {
		$queries = [];
		foreach ( $this->transaction as $queryStatement ) {
			$queries[] = $queryStatement->getBindParams();
		}

		return $queries;
	}
}