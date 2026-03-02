<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ItemMetadataTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilEncodingInterface;
use Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilTextServiceInterface;
use Spryker\Zed\SalesProductConnector\SalesProductConnectorConfig;

class SalesOrderItemMetadataMapper
{
    /**
     * @var \Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilEncodingInterface
     */
    protected SalesProductConnectorToUtilEncodingInterface $utilEncodingService;

    /**
     * @var \Spryker\Zed\SalesProductConnector\SalesProductConnectorConfig
     */
    protected SalesProductConnectorConfig $salesProductConnectorConfig;

    /**
     * @var \Spryker\Zed\SalesProductConnector\Dependency\Service\SalesProductConnectorToUtilTextServiceInterface
     */
    protected SalesProductConnectorToUtilTextServiceInterface $utilTextService;

    public function __construct(
        SalesProductConnectorToUtilEncodingInterface $utilEncodingService,
        SalesProductConnectorConfig $salesProductConnectorConfig,
        SalesProductConnectorToUtilTextServiceInterface $utilTextService
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->salesProductConnectorConfig = $salesProductConnectorConfig;
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata> $salesOrderItemMetadataEntities
     *
     * @return array<\Generated\Shared\Transfer\ItemMetadataTransfer>
     */
    public function mapSalesOrderItemMetadataEntityCollectionToItemMetadataTransfers(
        Collection $salesOrderItemMetadataEntities
    ): array {
        $itemMetadataTransfers = [];

        foreach ($salesOrderItemMetadataEntities as $salesOrderItemMetadataEntity) {
            $itemMetadataTransfers[] = (new ItemMetadataTransfer())
                ->fromArray($salesOrderItemMetadataEntity->toArray(), true)
                ->setSuperAttributes($this->utilEncodingService->decodeJson($salesOrderItemMetadataEntity->getSuperAttributes(), true));
        }

        return $itemMetadataTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<string, mixed> $superAttributes
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata|null $salesOrderItemMetadataEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata
     */
    public function mapItemTransferToSalesOrderItemMetadataEntity(
        ItemTransfer $itemTransfer,
        array $superAttributes,
        ?SpySalesOrderItemMetadata $salesOrderItemMetadataEntity = null
    ): SpySalesOrderItemMetadata {
        $image = $this->determineImage($itemTransfer);
        $entity = $salesOrderItemMetadataEntity ?? new SpySalesOrderItemMetadata();

        $metadata = $this->getMetaData($itemTransfer);
        $entity->fromArray($metadata);
        $entity->setSuperAttributes($this->utilEncodingService->encodeJson($superAttributes) ?? '');
        $entity->setFkSalesOrderItem($itemTransfer->getIdSalesOrderItemOrFail());
        if ($image) {
            $entity->setImage($image);
        }

        return $entity;
    }

    protected function determineImage(ItemTransfer $itemTransfer): ?string
    {
        $images = $itemTransfer->getImages();
        if (count($images) === 0) {
            return null;
        }

        $productImageTransfer = $images->offsetGet(0);

        return $productImageTransfer->getExternalUrlSmall();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array<string, mixed>
     */
    protected function getMetaData(ItemTransfer $itemTransfer): array
    {
        if (!$itemTransfer->getMetadata()) {
            return [];
        }

        $metadata = $itemTransfer->getMetadataOrFail()->toArray();
        $excludedAttributes = $this->salesProductConnectorConfig->getExcludedMetadataAttributes();
        $excludedAttributeMap = array_combine($excludedAttributes, $excludedAttributes);

        return array_filter(
            $metadata,
            function (string $key) use ($excludedAttributeMap): bool {
                $attribute = $this->utilTextService->separatorToCamelCase($key, '_');

                return !isset($excludedAttributeMap[$attribute]);
            },
            ARRAY_FILTER_USE_KEY,
        );
    }
}
