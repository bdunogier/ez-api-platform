<?php
/**
 * Created by PhpStorm.
 * User: bdunogier
 * Date: 09/12/2017
 * Time: 13:42
 */

namespace Ez\ApiPlatform\Repository\Filter;


use eZ\Publish\API\Repository\Values\Content\Query;
use Symfony\Component\HttpFoundation\RequestStack;

class ParentLocationIdFilter extends AbstractQueryFilter implements FilterInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function apply(Query $query, string $resourceClass, string $operationName)
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$parentLocationId = $request->query->get('parentLocationId')) {
            return;
        }

        $this->addCriterion($query, new Query\Criterion\ParentLocationId($parentLocationId));
    }

    /**
     * Gets the description of this filter for the given resource.
     *
     * Returns an array with the filter parameter names as keys and array with the following data as values:
     *   - property: the property where the filter is applied
     *   - type: the type of the filter
     *   - required: if this filter is required
     *   - strategy: the used strategy
     *   - swagger (optional): additional parameters for the path operation,
     *     e.g. 'swagger' => [
     *       'description' => 'My Description',
     *       'name' => 'My Name',
     *       'type' => 'integer',
     *     ]
     * The description can contain additional data specific to a filter.
     *
     * @see \ApiPlatform\Core\Swagger\Serializer\DocumentationNormalizer::getFiltersParameters
     *
     * @param string $resourceClass
     *
     * @return array
     */
    public function getDescription(string $resourceClass): array
    {
        return [];
    }
}