<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 8/1/2017
 * Time: 12:26 PM
 */

use Qpdb\QueryBuilder\Dependencies\Tree;
use Qpdb\QueryBuilder\QueryBuild;

include_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';


//var_dump(__DIR__);
//var_dump($_SERVER['DOCUMENT_ROOT']);


$query = QueryBuild::select( 'employees' )
	->fields('lastName, jobTitle, officeCode')
	->whereEqual( 'jobTitle', "Sales Rep" )
	->whereIn( 'officeCode', [ 2, 3, 4 ] );


echo "<pre>" . print_r( $query->getSyntax(), 1 ) . "</pre>";
echo "<pre>" . print_r( $query->getBindParams(), 1 ) . "</pre>";
echo "<pre>" . print_r( $query->getSyntax( 1 ), 1 ) . "</pre>";
echo "<pre>" . print_r( $query->execute(), 1 ) . "</pre>";

var_dump('page_select');

$a =
	[
		[
			'id' => 1,
			'parent' => 0,
			'name' => 'Option 1'
		],
		[
			'id' => 2,
			'parent' => 0,
			'name' => 'Option 2'
		],
		[
			'id' => 11,
			'parent' => 1,
			'name' => 'Option 11'
		],
		[
			'id' => 112,
			'parent' => 11,
			'name' => 'Option 112'
		],
		[
			'id' => 111,
			'parent' => 11,
			'name' => 'Option 111'
		],
		[
			'id' => 12,
			'parent' => 1,
			'name' => 'Option 121'
		],
		[
			'id' => 2224,
			'parent' => 78,
			'name' => 'bla bla'
		],
		[
			'id' => 1121,
			'parent' => 112,
			'name' => 'Option 1121'
		],
		[
			'id' => 1122,
			'parent' => 112,
			'name' => 'Option 1122'
		],
	];

$tree = ( new Tree( $a ) )
	->withIdName( 'id' )
	->withParentIdName( 'parent' )
	->buildTree();


//echo "<pre>" . print_r( $tree->getTreeArray(), 1 ) . "</pre>";
//echo "<pre>" . print_r( $tree->getChildren(1), 1 ) . "</pre>";
//echo "<pre>" . print_r( $tree->getParents(1121), 1 ) . "</pre>";
//echo "<pre>" . print_r( $tree->getFlatArray(), 1 ) . "</pre>";


