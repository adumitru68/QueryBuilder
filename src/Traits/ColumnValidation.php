<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 9/17/2017
 * Time: 5:16 AM
 */

namespace Qpdb\QueryBuilder\Traits;


trait ColumnValidation
{

    use Objects;

    protected function validateColumn($columnName, array $allowed)
    {
        if (is_integer($columnName))
            return true;

        if (!count($allowed))
            return true;

        return false;
    }


}