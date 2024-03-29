<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesProductConnector\Plugin;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\SortConfigTransfer;
use Spryker\Client\Catalog\Dependency\Plugin\SortConfigTransferBuilderPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

class PopularitySortConfigTransferBuilderPlugin extends AbstractPlugin implements SortConfigTransferBuilderPluginInterface
{
    /**
     * @var string
     */
    protected const CONFIG_NAME = 'popularity';

    /**
     * @var string
     */
    protected const PARAMETER_NAME = 'popularity';

    /**
     * @var string
     */
    protected const UNMAPPED_TYPE = 'integer';

    /**
     * {@inheritDoc}
     * - Builds a popularity sort configuration transfer for the catalog page.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SortConfigTransfer
     */
    public function build()
    {
        return (new SortConfigTransfer())
            ->setName(static::CONFIG_NAME)
            ->setParameterName(static::PARAMETER_NAME)
            ->setFieldName(PageIndexMap::INTEGER_SORT)
            ->setIsDescending(true)
            ->setUnmappedType(static::UNMAPPED_TYPE);
    }
}
