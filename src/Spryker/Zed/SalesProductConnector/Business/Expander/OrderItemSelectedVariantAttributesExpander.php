<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Business\Expander;

use Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface;

class OrderItemSelectedVariantAttributesExpander implements OrderItemSelectedVariantAttributesExpanderInterface
{
    public function __construct(protected SalesProductConnectorRepositoryInterface $salesProductConnectorRepository)
    {
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithSelectedVariantAttributes(array $itemTransfers): array
    {
        $superAttributesIndexedByIdSalesOrderItem = $this->getSuperAttributesIndexedByIdSalesOrderItem($itemTransfers);

        foreach ($itemTransfers as $itemTransfer) {
            $superAttributes = $superAttributesIndexedByIdSalesOrderItem[$itemTransfer->getIdSalesOrderItem()] ?? [];
            $itemTransfer->setSelectedVariantAttributes($this->getSelectedVariantAttributes($superAttributes));
        }

        return $itemTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int, array<int|string, mixed>>
     */
    protected function getSuperAttributesIndexedByIdSalesOrderItem(array $itemTransfers): array
    {
        $salesOrderItemIds = [];
        $superAttributesIndexedByIdSalesOrderItem = [];

        foreach ($itemTransfers as $itemTransfer) {
            $idSalesOrderItem = $itemTransfer->getIdSalesOrderItem();

            if (!$idSalesOrderItem) {
                continue;
            }

            // The metadata an earlier order item expander loaded, e.g. MetadataOrderItemExpanderPlugin, saves reading it again.
            if ($itemTransfer->getMetadata() !== null) {
                $superAttributesIndexedByIdSalesOrderItem[$idSalesOrderItem] = $itemTransfer->getMetadata()->getSuperAttributes();

                continue;
            }

            $salesOrderItemIds[$idSalesOrderItem] = $idSalesOrderItem;
        }

        if (!$salesOrderItemIds) {
            return $superAttributesIndexedByIdSalesOrderItem;
        }

        foreach ($this->salesProductConnectorRepository->getSalesOrderItemMetadataByOrderItemIds(array_values($salesOrderItemIds)) as $itemMetadataTransfer) {
            $superAttributesIndexedByIdSalesOrderItem[(int)$itemMetadataTransfer->getFkSalesOrderItem()] = $itemMetadataTransfer->getSuperAttributes();
        }

        return $superAttributesIndexedByIdSalesOrderItem;
    }

    /**
     * @param array<int|string, mixed> $superAttributes
     *
     * @return array<int|string, string>
     */
    protected function getSelectedVariantAttributes(array $superAttributes): array
    {
        $selectedVariantAttributes = [];

        foreach ($superAttributes as $superAttributeKey => $value) {
            $hasValue = is_scalar($value) && (string)$value !== '';

            if (!$hasValue) {
                continue;
            }

            $selectedVariantAttributes[$superAttributeKey] = (string)$value;
        }

        // String order, like the selected attributes of a cart item: PHP turns a numeric attribute key into an integer.
        ksort($selectedVariantAttributes, SORT_STRING);

        return $selectedVariantAttributes;
    }
}
