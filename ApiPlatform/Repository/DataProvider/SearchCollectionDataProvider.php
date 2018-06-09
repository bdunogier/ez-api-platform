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
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\Core\Repository\Values\Content\Location;
use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Search\SearchHit;

class SearchCollectionDataProvider implements CollectionDataProviderInterface
{
    /**
     * @var SearchService
     */
    protected $searchService;

    /**
     * @var FilterInterface[]
     */
    private $filters;

    /**
     * @var string
     */
    private $queryClass;

    /**
     * @var string
     */
    private $searchMethod;

    /**
     * @var string
     */
    private $resourceClass;

    public function __construct(SearchService $searchService, array $filters = [], string $queryClass, string $searchMethod, string $resourceClass)
    {
        $this->searchService = $searchService;
        $this->filters = $filters;
        $this->queryClass = $queryClass;
        $this->searchMethod = $searchMethod;
        $this->resourceClass = $resourceClass;
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
        if ($resourceClass != $this->resourceClass) {
            throw new ResourceClassNotSupportedException();
        }

        $buildQuery = $this->buildQuery($operationName, $operationName);
        return array_map(
            function (SearchHit $searchHit) {
                return $searchHit->valueObject;
            },
            $this->searchService->{$this->searchMethod}($buildQuery)->searchHits
        );
    }

    private function buildQuery(string $resourceClass, string $operationName)
    {
        $query = new $this->queryClass;
        foreach ($this->filters as $filter) {
            $filter->apply($query, $resourceClass, $operationName);
        }

        return $query;
    }
}