<?php
/**
 * Created by PhpStorm.
 * User: Adi
 * Date: 4/28/2018
 * Time: 10:32 AM
 */

namespace Qpdb\QueryBuilder\Traits;


trait AsSingleton
{

	protected static $instance;

	/**
	 * @return $this
	 */
	public static function getInstance()
	{
		if ( is_null( self::$instance ) )
			self::$instance = new self();

		return self::$instance;
	}

}