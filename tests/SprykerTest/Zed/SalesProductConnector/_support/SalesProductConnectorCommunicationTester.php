<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesProductConnector;

use Codeception\Actor;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadata;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemMetadataQuery;

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
class SalesProductConnectorCommunicationTester extends Actor
{
    use _generated\SalesProductConnectorCommunicationTesterActions;

    public function ensureSalesOrderItemMetadataDatabaseTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty(
            $this->getSalesOrderItemMetadataQuery(),
        );
    }

    public function getSalesOrderItemMetadataQuery(): SpySalesOrderItemMetadataQuery
    {
        return SpySalesOrderItemMetadataQuery::create();
    }

    public function findSalesOrderItemMetadata(int $idSalesOrderItem): SpySalesOrderItemMetadata
    {
        return $this->getSalesOrderItemMetadataQuery()
            ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->findOne();
    }
}
