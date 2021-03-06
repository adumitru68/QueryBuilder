# QueryBuilder v2

**QueryBuilder** is a user friendly php class for build MySql queries that prevents mysql injections and it takes care of table prefixing. This same can also replication support for use master and slave.

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/adumitru68/QueryBuilder/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/adumitru68/QueryBuilder/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/adumitru68/QueryBuilder/badges/build.png?b=master)](https://scrutinizer-ci.com/g/adumitru68/QueryBuilder/build-status/master)

[Old versions documentation](docs/v1_config.md)

### Requirements
* Php 5.6+
* Enable PDO (php.ini)
* MySql 5.5 / 5.6 / 5.7 / MariaDB
* Partial tested for MySql 8

### Installation

```
composer require qpdb/query-builder
```

### Configuration

It is enough to configure the [pdoWrapper](https://github.com/adumitru68/PdoWrapper/blob/master/README.md) dependence.

### How do we use?
```php
include_once 'path/to/vendor/autoload.php';

use Qpdb\QueryBuilder\QueryBuild;

$query = QueryBuild::select( 'employees' )
	->fields('lastName, jobTitle, officeCode')
	->whereEqual( 'jobTitle', "Sales Rep" )
	->whereIn( 'officeCode', [ 2, 3, 4 ] );
	
$query->execute() /** return array */
	
Array
(
    [0] => Array
        (
            [lastName] => Firrelli
            [jobTitle] => Sales Rep
            [officeCode] => 2
        )

    [1] => Array
        (
            [lastName] => Patterson
            [jobTitle] => Sales Rep
            [officeCode] => 2
        )
    ...
)
```
