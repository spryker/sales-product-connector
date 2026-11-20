<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Communication\Plugin\Sales;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface;

/**
 * @method \Spryker\Zed\SalesProductConnector\Business\SalesProductConnectorBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\SalesProductConnector\Business\SalesProductConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\SalesProductConnector\SalesProductConnectorConfig getConfig()
 */
class SelectedVariantAttributesOrderItemExpanderPlugin extends AbstractPlugin implements OrderItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Reads the item metadata of the order items by `ItemTransfer.idSalesOrderItem` with one request.
     * - Sets `ItemTransfer.selectedVariantAttributes` to the super attributes of the item metadata, attribute key to value,
     *   ordered by attribute key as strings, e.g. ["color" => "Red", "storage_capacity" => "64 GB"]; empty and non-scalar values are left out.
     * - Sets an empty `ItemTransfer.selectedVariantAttributes` for an item without `idSalesOrderItem` or without item metadata.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expand(array $itemTransfers): array
    {
        return $this->getBusinessFactory()
            ->createOrderItemSelectedVariantAttributesExpander()
            ->expandOrderItemsWithSelectedVariantAttributes($itemTransfers);
    }
}
