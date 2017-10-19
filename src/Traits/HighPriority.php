<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 8/1/2017
 * Time: 4:48 PM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryStructure;

trait HighPriority
{

    use Objects;

    /**
     * @return $this
     */
    public function highPriority()
    {
        $this->queryStructure->setElement(QueryStructure::PRIORITY, 'HIGH_PRIORITY');

        return $this;
    }

}