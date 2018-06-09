<?php
/**
 * Created by PhpStorm.
 * User: bdunogier
 * Date: 08/12/2017
 * Time: 17:41
 */

namespace Ez\ApiPlatform\Repository\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use eZ\Publish\API\Repository\Values\Content\Query;

class LocationCollectionDataProvider extends SearchCollectionDataProvider implements CollectionDataProviderInterface
{
    protected function runSearch(Query $query)
    {
    }

    protected function createQuery()
    {
        // TODO: Implement createQuery() method.
    }
}