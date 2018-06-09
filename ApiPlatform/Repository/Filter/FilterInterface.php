<?php
namespace Ez\ApiPlatform\Repository\Filter;

use ApiPlatform\Core\Api\FilterInterface as BaseFilterInterface;
use eZ\Publish\API\Repository\Values\Content\Query;

interface FilterInterface extends BaseFilterInterface
{
    public function apply(Query $query, string $resourceClass, string $operationName);
}