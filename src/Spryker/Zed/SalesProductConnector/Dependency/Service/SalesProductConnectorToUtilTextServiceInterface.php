<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Dependency\Service;

interface SalesProductConnectorToUtilTextServiceInterface
{
    public function separatorToCamelCase(string $string, string $separator = '-', bool $upperCaseFirst = false): string;
}
