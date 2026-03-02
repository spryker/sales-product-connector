<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Business\Deleter;

use Generated\Shared\Transfer\SalesOrderItemMetadataCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemMetadataCollectionResponseTransfer;
use Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorEntityManagerInterface;

class SalesOrderItemMetadataDeleter implements SalesOrderItemMetadataDeleterInterface
{
    public function __construct(protected SalesProductConnectorEntityManagerInterface $salesProductConnectorEntityManager)
    {
    }

    public function deleteSalesOrderItemMetadataCollection(
        SalesOrderItemMetadataCollectionDeleteCriteriaTransfer $salesOrderItemMetadataCollectionDeleteCriteriaTransfer
    ): SalesOrderItemMetadataCollectionResponseTransfer {
        if ($salesOrderItemMetadataCollectionDeleteCriteriaTransfer->getSalesOrderItemIds()) {
            $this->salesProductConnectorEntityManager->deleteSalesOrderItemMetadataCollectionBySalesOrderItemIds(
                $salesOrderItemMetadataCollectionDeleteCriteriaTransfer->getSalesOrderItemIds(),
            );
        }

        return new SalesOrderItemMetadataCollectionResponseTransfer();
    }
}
