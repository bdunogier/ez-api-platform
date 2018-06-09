<?php
/**
 * Created by PhpStorm.
 * User: bdunogier
 * Date: 25/11/2017
 * Time: 13:20
 */

namespace Ez\ApiPlatform\Repository\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\Core\Repository\Values\Content\Location;

final class LocationItemDataProvider implements ItemDataProviderInterface
{
    /**
     * @var LocationService
     */
    private $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * Retrieves an item.
     *
     * @param string $resourceClass
     * @param int|string $id
     * @param string|null $operationName
     * @param array $context
     *
     * @throws ResourceClassNotSupportedException
     *
     * @return object|null
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        if (Location::class !== $resourceClass) {
            throw new ResourceClassNotSupportedException();
        }

        try {
            return $this->locationService->loadLocation($id);
        } catch (NotFoundException $e) {
            return null;
        }
    }
}
