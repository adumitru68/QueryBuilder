<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 8/1/2017
 * Time: 4:43 PM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryStructure;

trait DefaultPriority
{

    use Objects;


    /**
     * @return $this
     */
    public function defaultPriority()
    {
        $this->queryStructure->setElement(QueryStructure::PRIORITY, '');

        return $this;
    }

}