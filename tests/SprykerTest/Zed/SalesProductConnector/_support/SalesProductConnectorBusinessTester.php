<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesProductConnector;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadataQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\SalesProductConnector\Communication\Plugin\Checkout\ItemMetadataSaverPlugin;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method void pause()
 * @method \Spryker\Zed\SalesProductConnector\Business\SalesProductConnectorFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesProductConnectorBusinessTester extends Actor
{
    use _generated\SalesProductConnectorBusinessTesterActions;

    public function createOrderByStateMachineProcessName(string $stateMachineProcessName): OrderTransfer
    {
        $quoteTransfer = $this->buildFakeQuote(
            $this->haveCustomer(),
            $this->haveStore([StoreTransfer::NAME => 'DE']),
        );

        $saveOrderTransfer = $this->haveOrderFromQuote($quoteTransfer, $stateMachineProcessName, [new ItemMetadataSaverPlugin()]);

        return (new OrderTransfer())
            ->setIdSalesOrder($saveOrderTransfer->getIdSalesOrder())
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setStore($quoteTransfer->getStore()->getName())
            ->setCustomer($quoteTransfer->getCustomer())
            ->setItems($saveOrderTransfer->getOrderItems());
    }

    public function ensureSalesOrderDatabaseTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getSalesOrderQuery());
    }

    public function createQuote(): QuoteTransfer
    {
        return $this->buildFakeQuote(
            $this->haveCustomer(),
            $this->haveStore([StoreTransfer::NAME => 'DE']),
        );
    }

    public function createSaveOrder(QuoteTransfer $quoteTransfer, string $stateMachineProcessName): SaveOrderTransfer
    {
        return $this->haveOrderFromQuote($quoteTransfer, $stateMachineProcessName, [new ItemMetadataSaverPlugin()]);
    }

    /**
     * @param list<int> $salesOrderItemIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata>
     */
    public function getSalesOrderItemMetadataEntities(array $salesOrderItemIds): ObjectCollection
    {
        return $this->getSalesOrderItemMetadataQuery()
            ->filterByFkSalesOrderItem_In($salesOrderItemIds)
            ->find();
    }

    public function haveSalesOrderItemMetaData(int $idSalesOrderItem): int
    {
        $salesOrderItemMetadataEntity = (new SpySalesOrderItemMetadata())
            ->setFkSalesOrderItem($idSalesOrderItem)
            ->setSuperAttributes('[]');
        $salesOrderItemMetadataEntity->save();

        return $salesOrderItemMetadataEntity->getIdSalesOrderItemMetadata();
    }

    public function getSalesOrderItemMetadataQuery(): SpySalesOrderItemMetadataQuery
    {
        return SpySalesOrderItemMetadataQuery::create();
    }

    protected function buildFakeQuote(CustomerTransfer $customerTransfer, StoreTransfer $storeTransfer): QuoteTransfer
    {
        $productConcreteTransfer = $this->haveProduct();

        $productImageSetTransfer = $this->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
        ]);

        $quoteTransfer = (new QuoteBuilder())
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();

        $quoteTransfer
            ->setCustomer($customerTransfer)
            ->setStore($storeTransfer)
            ->addItem(
                (new ItemBuilder())->build()
                    ->setSku($productConcreteTransfer->getSku())
                    ->setImages($productImageSetTransfer->getProductImages()),
            );

        return $quoteTransfer;
    }

    protected function getSalesOrderQuery(): SpySalesOrderQuery
    {
        return SpySalesOrderQuery::create();
    }
}
