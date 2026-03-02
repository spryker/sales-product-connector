<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Business\Deleter;

use Generated\Shared\Transfer\SalesOrderItemMetadataCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemMetadataCollectionResponseTransfer;

interface SalesOrderItemMetadataDeleterInterface
{
    public function deleteSalesOrderItemMetadataCollection(
        SalesOrderItemMetadataCollectionDeleteCriteriaTransfer $salesOrderItemMetadataCollectionDeleteCriteriaTransfer
    ): SalesOrderItemMetadataCollectionResponseTransfer;
}
