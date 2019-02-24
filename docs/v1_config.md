# QueryBuilder v1

**QueryBuilder** is a user friendly php class for build MySql queries that prevents mysql injections and it takes care of table prefixing. This same can also replication support for use master and slave.

### Requirements
* Php 5.4+
* Enable PDO (php.ini)
* MySql 5.5 / 5.6 / 5.7 / MariaDB

### Installation

```
composer require qpdb/query-builder
```
If you do not use composer, you can [manually install](installation/manual.md) this library.

### Configuration

By default MysqlBuilder searches for configuration data (credentials, paths, etc) in the file 
```vendor/qpdb/query_builder/sample-config/qpdb_db_config.php```. 
But it's not recommended to edit this file because it's overwritten by composer.
The simplest way to set up an efficient Query Builder is to walk through three easy steps:
- Create a ```vendor-cfg``` folder on the same level as the ```vendor``` folder.
- Copy file ```qpdb_db_config.php``` into ```vendor-cfg```.
- Edit file ```vendor-cfg/qpdb_db_config.php```.

Of course there are other configuration options (With Composer, or using custom autoload file), based on the DbConfig class. 
These are listed in the [Configuration](installation/config2.md) section.

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
