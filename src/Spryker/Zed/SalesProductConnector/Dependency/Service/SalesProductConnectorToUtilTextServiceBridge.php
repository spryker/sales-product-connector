<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Dependency\Service;

class SalesProductConnectorToUtilTextServiceBridge implements SalesProductConnectorToUtilTextServiceInterface
{
    /**
     * @var \Spryker\Service\UtilText\UtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Service\UtilText\UtilTextServiceInterface $utilTextService
     */
    public function __construct($utilTextService)
    {
        $this->utilTextService = $utilTextService;
    }

    public function separatorToCamelCase(string $string, string $separator = '-', bool $upperCaseFirst = false): string
    {
        return $this->utilTextService->separatorToCamelCase($string, $separator, $upperCaseFirst);
    }
}
