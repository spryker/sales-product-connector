<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesProductConnector\Communication\Plugin\Sales;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\SalesProductConnector\Communication\Plugin\Sales\SelectedVariantAttributesOrderItemExpanderPlugin;
use SprykerTest\Zed\SalesProductConnector\SalesProductConnectorCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesProductConnector
 * @group Communication
 * @group Plugin
 * @group Sales
 * @group SelectedVariantAttributesOrderItemExpanderPluginTest
 * Add your own group annotations below this line
 */
class SelectedVariantAttributesOrderItemExpanderPluginTest extends Unit
{
    protected const string DEFAULT_OMS_PROCESS_NAME = 'Test01';

    protected const string SUPER_ATTRIBUTE_KEY_COLOR = 'color';

    protected const string SUPER_ATTRIBUTE_KEY_STORAGE = 'storage_capacity';

    /**
     * PHP turns this key into an integer, which sorts before the string keys in string order.
     */
    protected const string SUPER_ATTRIBUTE_KEY_NUMERIC = '10';

    protected const string SUPER_ATTRIBUTE_KEY_EMPTY = 'size';

    protected const string COLOR = 'Red';

    protected const string STORAGE = '64 GB';

    protected const int NUMERIC_VALUE = 100;

    protected SalesProductConnectorCommunicationTester $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    public function testExpandSetsTheNonEmptySuperAttributesOfTheItemMetadataInTheStringOrderOfTheirKeys(): void
    {
        // Arrange
        $idSalesOrderItem = $this->tester->haveOrder([], static::DEFAULT_OMS_PROCESS_NAME)->getOrderItems()->offsetGet(0)->getIdSalesOrderItemOrFail();
        $this->tester->haveSalesOrderItemMetadata($idSalesOrderItem, [
            static::SUPER_ATTRIBUTE_KEY_STORAGE => static::STORAGE,
            static::SUPER_ATTRIBUTE_KEY_EMPTY => '',
            static::SUPER_ATTRIBUTE_KEY_COLOR => static::COLOR,
            static::SUPER_ATTRIBUTE_KEY_NUMERIC => static::NUMERIC_VALUE,
        ]);

        // Act
        $itemTransfers = (new SelectedVariantAttributesOrderItemExpanderPlugin())->expand([(new ItemTransfer())->setIdSalesOrderItem($idSalesOrderItem)]);

        // Assert
        $this->assertSame(
            [static::SUPER_ATTRIBUTE_KEY_NUMERIC => (string)static::NUMERIC_VALUE, static::SUPER_ATTRIBUTE_KEY_COLOR => static::COLOR, static::SUPER_ATTRIBUTE_KEY_STORAGE => static::STORAGE],
            $itemTransfers[0]->getSelectedVariantAttributes(),
        );
    }

    public function testExpandSetsNoSelectedVariantAttributesForAnItemWithoutIdSalesOrderItem(): void
    {
        // Act
        $itemTransfers = (new SelectedVariantAttributesOrderItemExpanderPlugin())->expand([new ItemTransfer()]);

        // Assert
        $this->assertSame([], $itemTransfers[0]->getSelectedVariantAttributes());
    }
}
