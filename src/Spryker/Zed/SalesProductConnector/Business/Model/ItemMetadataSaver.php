<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorEntityManagerInterface;
use Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface;

class ItemMetadataSaver implements ItemMetadataSaverInterface
{
    use TransactionTrait;

    public function __construct(
        protected SalesProductConnectorEntityManagerInterface $salesProductConnectorEntityManager,
        protected SalesProductConnectorRepositoryInterface $salesProductConnectorRepository
    ) {
    }

    public function saveItemsMetadata(QuoteTransfer $quoteTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer): void {
            $this->executeSaveItemsMetadataTransaction($quoteTransfer);
        });
    }

    public function updateOrderItemMetadata(
        SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        $quoteTransfer = (new QuoteTransfer())->setItems($salesOrderItemCollectionResponseTransfer->getItems());

        $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer): void {
            $this->executeUpdateItemsMetadataTransaction($quoteTransfer);
        });

        return $salesOrderItemCollectionResponseTransfer;
    }

    protected function executeSaveItemsMetadataTransaction(QuoteTransfer $quoteTransfer): void
    {
        $this->salesProductConnectorEntityManager->saveItemsMetadata(
            $quoteTransfer,
            $this->salesProductConnectorRepository->getSupperAttributesGroupedByIdItem($quoteTransfer),
        );
    }

    protected function executeUpdateItemsMetadataTransaction(QuoteTransfer $quoteTransfer): void
    {
        $this->salesProductConnectorEntityManager->saveItemsMetadataByFkSalesOrderItem(
            $quoteTransfer,
            $this->salesProductConnectorRepository->getSupperAttributesGroupedByIdItem($quoteTransfer),
        );
    }
}
