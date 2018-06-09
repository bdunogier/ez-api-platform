<?php
/**
 * Created by PhpStorm.
 * User: bdunogier
 * Date: 09/12/2017
 * Time: 18:31
 */

namespace Ez\ApiPlatform\Repository\Filter;

use eZ\Publish\API\Repository\Values\Content\Query;

class AbstractQueryFilter
{
    protected function addCriterion(Query $query, Query\Criterion $criterion)
    {
        if ($query->filter === null) {
            $query->filter = $criterion;
        } elseif ($query->filter instanceof Query\Criterion\LogicalOperator) {
            $query->filter->criteria[] = $criterion;
        } else {
            $query->filter = new Query\Criterion\LogicalAnd([$criterion]);
        }
    }
}