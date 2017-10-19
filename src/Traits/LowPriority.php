<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 8/1/2017
 * Time: 4:57 PM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryStructure;

trait LowPriority
{

    use Objects;

    /**
     * @return $this
     */
    public function lowPriority()
    {
        $this->queryStructure->setElement(QueryStructure::PRIORITY, 'LOW_PRIORITY');

        return $this;
    }

}