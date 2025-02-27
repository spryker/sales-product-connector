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
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesProductConnector\Business\SalesProductConnectorBusinessFactory getFactory()
 * @method \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorEntityManagerInterface getEntityManager()
 */
class SalesProductConnectorFacade extends AbstractFacade implements SalesProductConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
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
    public function saveItemMetadata(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFactory()
            ->createItemMetadataSaver()
            ->saveItemsMetadata($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderItemMetadata(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        $this->getFactory()
            ->createItemMetadataSaver()
            ->saveItemsMetadata($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link expandOrderItemsWithMetadata()} instead.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateItemMetadata(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createItemMetadataHydrator()->hydrateItemMetadata($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link SalesProductConnectorFacade::expandOrderItemsWithProductIds()} instead.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateProductIds(OrderTransfer $orderTransfer)
    {
        return $this->getFactory()->createProductIdHydrator()->hydrateProductIds($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithMetadata(array $itemTransfers): array
    {
        return $this->getFactory()
            ->createItemMetadataExpander()
            ->expandOrderItemsWithMetadata($itemTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     *
     * @return array<\Generated\Shared\Transfer\OrderTransfer>
     */
    public function expandOrdersWithMetadata(array $orderTransfers): array
    {
        return $this->getFactory()
            ->createOrderExpander()
            ->expandOrdersWithMetadata($orderTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandOrderItemsWithProductIds(array $itemTransfers): array
    {
        return $this->getFactory()
            ->createProductIdExpander()
            ->expandOrderItemsWithProduct($itemTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $productPageLoadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductAbstractPageWithPopularity(ProductPageLoadTransfer $productPageLoadTransfer): ProductPageLoadTransfer
    {
        return $this->getFactory()
            ->createPopularityProductPageExpander()
            ->expandProductPageLoadTransferWithPopularityData($productPageLoadTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function getProductPageLoadTransferForRefresh(): ProductPageLoadTransfer
    {
        return $this->getFactory()->createProductAbstractIdsRefreshReader()->getProductProductPageLoadTransferForRefresh();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderItemMetadataCollectionDeleteCriteriaTransfer $salesOrderItemMetadataCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemMetadataCollectionResponseTransfer
     */
    public function deleteSalesOrderItemMetadataCollection(
        SalesOrderItemMetadataCollectionDeleteCriteriaTransfer $salesOrderItemMetadataCollectionDeleteCriteriaTransfer
    ): SalesOrderItemMetadataCollectionResponseTransfer {
        return $this->getFactory()
            ->createSalesOrderItemMetadataDeleter()
            ->deleteSalesOrderItemMetadataCollection($salesOrderItemMetadataCollectionDeleteCriteriaTransfer);
    }
}
