<?php
namespace Ez\ApiPlatform\Repository\Filter;

use eZ\Publish\API\Repository\Values\Content\Query;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Filters a collection using an eZ Platform API criterion.
 */
class CriterionFilter extends AbstractQueryFilter implements FilterInterface
{
    /**
     * @var string
     */
    private $criterionClass;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack, string $criterionClass)
    {
        if (!in_array('eZ\Publish\API\Repository\Values\Content\Query\Criterion', class_parents($criterionClass))) {
            throw new \InvalidArgumentException("criterionClass must be a descendant of eZ\Publish\API\Repository\Values\Content\Query\Criterion");
        }

        $this->criterionClass = $criterionClass;
        $this->requestStack = $requestStack;
    }

    public function apply(Query $query, string $resourceClass, string $operationName)
    {
        if (!$parameter = $this->extractParameter($this->requestStack->getCurrentRequest())) {
            return;
        }

        $this->addCriterion($query, new $this->criterionClass($parameter));
    }

    public function getDescription(string $resourceClass): array
    {
        return [];
    }

    private function extractParameter(Request $request)
    {
        return $request->query->get($this->getParameterName());
    }

    /**
     * @return string
     */
    private function getParameterName(): string
    {
        $namespaceParts = explode('\\', $this->criterionClass);

        return lcfirst(preg_replace('/Criterion$/', '', array_pop($namespaceParts)));
    }
}