<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Business\Reader;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface;
use Spryker\Zed\SalesProductConnector\SalesProductConnectorConfig;

class ProductAbstractIdsRefreshReader implements ProductAbstractIdsRefreshReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface
     */
    protected $salesProductConnectorRepository;

    /**
     * @var \Spryker\Zed\SalesProductConnector\SalesProductConnectorConfig
     */
    protected $salesProductConnectorConfig;

    public function __construct(
        SalesProductConnectorRepositoryInterface $salesProductConnectorRepository,
        SalesProductConnectorConfig $salesProductConnectorConfig
    ) {
        $this->salesProductConnectorRepository = $salesProductConnectorRepository;
        $this->salesProductConnectorConfig = $salesProductConnectorConfig;
    }

    public function getProductProductPageLoadTransferForRefresh(): ProductPageLoadTransfer
    {
        $interval = $this->salesProductConnectorConfig->getProductPageDataRefreshDaysInterval();

        $abstractProductIds = $this->salesProductConnectorRepository
            ->getProductAbstractIdsForRefreshByInterval($interval);

        return (new ProductPageLoadTransfer())
            ->setProductAbstractIds($abstractProductIds);
    }
}
