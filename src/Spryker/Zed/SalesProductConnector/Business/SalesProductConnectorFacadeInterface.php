<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderItemMetadataCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemMetadataCollectionResponseTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

interface SalesProductConnectorFacadeInterface
{
    /**
     * Specification:
     * - Saves product metadata information (image, super attributes) into a sales table to hydrate them later
     *
     * @api
     *
     * @deprecated Use {@link saveOrderItemMetadata()} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveItemMetadata(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse);

    /**
     * Specification:
     * - Saves product metadata information (image, super attributes) into a sales table to hydrate them later
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderItemMetadata(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer);

    /**
     * Specification:
     * - Hydrates product meta information (image, super attributes) into an order transfer
     *
     * @api
     *
     * @deprecated Use {@link expandOrderItemsWithMetadata()} instead.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateItemMetadata(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Hydrates product ids (abstract / concrete) into an order based on their sku
     *
     * @api
     *
     * @deprecated Use {@link SalesProductConnectorFacade::expandOrderItemsWithProductIds()} instead.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateProductIds(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Expands order items with metadata information.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithMetadata(array $itemTransfers): array;

    /**
     * Specification:
     * - Expands OrderTransfers with metadata information.
     * - Requires OrderTransfer::idSalesOrder to be set.
     * - Requires ItemTransfer::fkSalesOrder at OrderTransfer::items to be set.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     *
     * @return array<\Generated\Shared\Transfer\OrderTransfer>
     */
    public function expandOrdersWithMetadata(array $orderTransfers): array;

    /**
     * Specification:
     * - Hydrates product ids (abstract / concrete) into an order items based on their skus.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithProductIds(array $itemTransfers): array;

    /**
     * Specification:
     * - Expands `ProductPageLoadTransfer` with popularity data and returns the modified object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductAbstractPageWithPopularity(
        ProductPageLoadTransfer $productPageLoadTransfer
    ): ProductPageLoadTransfer;

    /**
     * Specification:
     * - Retrieves the list of productAbstractIds which need refresh.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function getProductPageLoadTransferForRefresh(): ProductPageLoadTransfer;

    /**
     * Specification:
     * - Uses `SalesOrderItemMetadataCollectionDeleteCriteriaTransfer.salesOrderItemIds` to filter sales order item metadata entities by the sales order item IDs.
     * - Deletes found by criteria sales order item metadata entities.
     * - Does nothing if no criteria properties are set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderItemMetadataCollectionDeleteCriteriaTransfer $salesOrderItemMetadataCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemMetadataCollectionResponseTransfer
     */
    public function deleteSalesOrderItemMetadataCollection(
        SalesOrderItemMetadataCollectionDeleteCriteriaTransfer $salesOrderItemMetadataCollectionDeleteCriteriaTransfer
    ): SalesOrderItemMetadataCollectionResponseTransfer;
}
