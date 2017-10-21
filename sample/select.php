<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 8/1/2017
 * Time: 12:26 PM
 */

use Qpdb\QueryBuilder\QueryBuild;

include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/vendor/autoload.php';


$query = QueryBuild::select( 'employees' )
	->fields( 'lastName, jobTitle, officeCode' )
	->whereEqual( 'jobTitle', "Sales Rep" )
	->whereIn( 'officeCode', [ 2, 3, 4 ] );

$count = QueryBuild::select( 'employees' )
	->fields( 'lastName, jobTitle, officeCode' )
	->whereEqual( 'jobTitle', "Sales Rep" )
	->whereIn( 'officeCode', [ 2, 3, 4 ] )
	->count();

$desc = QueryBuild::query('show tables');

echo "<pre>" . print_r( $count->execute(), 1 ) . "</pre>";
echo "<pre>" . print_r( $desc->execute(), 1 ) . "</pre>";


echo "<pre>" . print_r( $query->getSyntax(), 1 ) . "</pre>";
echo "<pre>" . print_r( $query->getBindParams(), 1 ) . "</pre>";
echo "<pre>" . print_r( $query->getSyntax( 1 ), 1 ) . "</pre>";
echo "<pre>" . print_r( $query->execute(), 1 ) . "</pre>";