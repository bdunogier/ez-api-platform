<?php

/*
 * This file is part of the API Platform project.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Ez\ApiPlatform\Metadata\Property\Factory;

use ApiPlatform\Core\Metadata\Property\Factory\PropertyMetadataFactoryInterface;
use ApiPlatform\Core\Metadata\Property\PropertyMetadata;
use ApiPlatform\Core\Metadata\Property\SubresourceMetadata;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;use eZ\Publish\API\Repository\Values\Content\Location as ApiLocation;
use eZ\Publish\API\Repository\Values\Content\Section;
use eZ\Publish\Core\Repository\Values\Content\Location as CoreLocation;use Symfony\Component\PropertyInfo\Type;

/**
 * Adds subresources to the properties metadata from {@see ApiResource} annotations.
 *
 * @author Antoine Bluchet <soyuka@gmail.com>
 */
final class ValueObjectsPropertyMetadataFactory implements PropertyMetadataFactoryInterface
{
    private $decorated;

    public function __construct(PropertyMetadataFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $resourceClass, string $property, array $options = []): PropertyMetadata
    {
        echo $resourceClass."/".$property."\n";
        $propertyMetadata = $this->decorated->create($resourceClass, $property, $options);

        if ($resourceClass == ApiLocation::class || $resourceClass == CoreLocation::class) {
            if ($property == 'contentInfo') {
                $propertyMetadata = $propertyMetadata
                    ->withType(new Type(
                        Type::BUILTIN_TYPE_OBJECT,
                        false,
                        ContentInfo::class,
                        false
                    ))
                    ->withSubresource(
                        new SubresourceMetadata(ContentInfo::class)
                    );
            }

            if ($property == 'section') {
                $propertyMetadata = $propertyMetadata
                    ->withType(new Type(
                        Type::BUILTIN_TYPE_OBJECT,
                        false,
                        Section::class,
                        false
                    ))
                    ->withSubresource(
                        new SubresourceMetadata(Section::class)
                    );
            }
        }

        return $propertyMetadata;
    }
}
