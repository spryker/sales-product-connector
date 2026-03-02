<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Business\Expander;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface;
use Spryker\Zed\SalesProductConnector\SalesProductConnectorConfig;

class PopularityProductPageExpander implements PopularityProductPageExpanderInterface
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

    public function expandProductPageLoadTransferWithPopularityData(
        ProductPageLoadTransfer $productPageLoadTransfer
    ): ProductPageLoadTransfer {
        $productAbstractIds = array_filter($productPageLoadTransfer->getProductAbstractIds());
        if (!$productAbstractIds) {
            return $productPageLoadTransfer;
        }

        $interval = $this->salesProductConnectorConfig->getPopularityDaysInterval();

        $popularityByIdProductAbstractAndInterval = $this->salesProductConnectorRepository
            ->getRawProductPopularityByProductAbstractIdsAndInterval(
                $productAbstractIds,
                $interval,
            );

        $payloadTransfers = $this->updatePayloadTransfers(
            $productPageLoadTransfer->getPayloadTransfers(),
            $popularityByIdProductAbstractAndInterval,
        );

        $productPageLoadTransfer->setPayloadTransfers($payloadTransfers);

        return $productPageLoadTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductPayloadTransfer> $productPageLoadTransfers
     * @param array<mixed> $popularityByIdProductAbstractAndInterval
     *
     * @return array<\Generated\Shared\Transfer\ProductPayloadTransfer> updated payload transfers
     */
    protected function updatePayloadTransfers(array $productPageLoadTransfers, array $popularityByIdProductAbstractAndInterval): array
    {
        foreach ($productPageLoadTransfers as $productPageLoadTransfer) {
            $popularityByIdProductAbstract = $popularityByIdProductAbstractAndInterval[$productPageLoadTransfer->getIdProductAbstract()] ?? null;

            $popularity = 0;
            if ($popularityByIdProductAbstract) {
                $popularity = (int)$popularityByIdProductAbstract[ProductPayloadTransfer::POPULARITY];
            }

            $productPageLoadTransfer->setPopularity($popularity);
        }

        return $productPageLoadTransfers;
    }
}
