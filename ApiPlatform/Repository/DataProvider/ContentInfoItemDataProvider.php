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
use Ez\ApiPlatform\Repository\Entity\Content\Location as RestLocation;
use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\Values\Content\Location as ApiLocation;

final class ContentInfoItemDataProvider implements ItemDataProviderInterface
{
    /**
     * @var ContentService
     */
    private $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
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
        if (ContentInfo::class !== $resourceClass) {
            throw new ResourceClassNotSupportedException();
        }

        try {
            return $this->contentService->loadContentInfo($id);
            // return $this->mapToRestLocation($this->locationService->loadLocation($id));
        } catch (NotFoundException $e) {
            return null;
        }
    }

    /**
     * @param \eZ\Publish\API\Repository\Values\Content\Location $apiLocation
     * @return \Ez\ApiPlatform\Repository\Entity\Content\Location
     */
    private function mapToRestLocation(ApiLocation $apiLocation)
    {
        $location = new RestLocation([
            'id' => $apiLocation->id,
            'contentInfo' => $apiLocation->getContentInfo(),
            'pathString' => $apiLocation->pathString,
            'hidden' => $apiLocation->hidden,
            'sortOrder' => $apiLocation->sortOrder,
            'sortField' => $apiLocation->sortField,
        ]);

        return $location;
    }
}
