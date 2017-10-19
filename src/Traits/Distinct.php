<?php
/**
 * Created by PhpStorm.
 * User: Adrian Dumitru
 * Date: 7/28/2017
 * Time: 8:04 AM
 */

namespace Qpdb\QueryBuilder\Traits;


use Qpdb\QueryBuilder\Dependencies\QueryException;
use Qpdb\QueryBuilder\Dependencies\QueryStructure;

trait Distinct
{

    use Objects;


    /**
     * @return $this
     */
    public function all()
    {
        $this->queryStructure->setElement(QueryStructure::DISTINCT, 0);

        return $this;
    }

    /**
     * @return $this
     */
    public function distinct()
    {
        $this->queryStructure->setElement(QueryStructure::DISTINCT, 1);

        return $this;
    }

    /**
     * @return $this
     */
    public function distinctRow()
    {
        $this->queryStructure->setElement(QueryStructure::DISTINCT, 2);

        return $this;
    }


    /**
     * @return string
     * @throws QueryException
     */
    private function getDistinctSyntax()
    {
        $useDistinct = $this->queryStructure->getElement(QueryStructure::DISTINCT);

        switch ($useDistinct) {
            case 0:
                return '';
            case 1:
                return 'DISTINCT';
            case 2:
                return 'DISTINCTROW';
            default:
                throw new QueryException('Invalid distinct type', QueryException::QUERY_ERROR_INVALID_DISTINCT);
        }

    }


}