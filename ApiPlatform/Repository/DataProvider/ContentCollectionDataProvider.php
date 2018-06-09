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
use Ez\ApiPlatform\Repository\Filter\FilterInterface;
use eZ\Publish\API\Repository\PermissionResolver;
use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\SearchService;
use eZ\Publish\Core\Repository\Values\Content\Location;
use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Search\SearchHit;

class LocationCollectionDataProvider implements CollectionDataProviderInterface
{
    /**
     * @var SearchService
     */
    private $searchService;

    /**
     * @var FilterInterface[]
     */
    private $filters;

    public function __construct(SearchService $searchService, array $filters = [])
    {
        $this->searchService = $searchService;
        $this->filters = $filters;
    }

    /**
     * Retrieves a collection.
     *
     * @param string $resourceClass
     * @param string|null $operationName
     *
     * @throws ResourceClassNotSupportedException
     *
     * @return array|\Traversable
     */
    public function getCollection(string $resourceClass, string $operationName = null)
    {
        if ($resourceClass !== Location::class) {
            throw new ResourceClassNotSupportedException();
        }

        return array_map(
            function (SearchHit $searchHit) {
                return $searchHit->valueObject;
            },
            $this->searchService->findLocations($this->buildQuery($resourceClass, $operationName))->searchHits
        );
    }

    private function buildQuery(string $resourceClass, string $operationName)
    {
        $query = new LocationQuery();
        foreach ($this->filters as $filter) {
            $filter->apply($query, $resourceClass, $operationName);
        }

        return $query;
    }
}