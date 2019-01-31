<?php
/**
 * Blackbird Catalog Default Qty Module
 *
 * NOTICE OF LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@bird.eu so we can send you a copy immediately.
 *
 * @category        Blackbird
 * @package         Blackbird_CatalogDefaultQty
 * @copyright       Copyright (c) Blackbird (https://black.bird.eu)
 * @author          Blackbird Team
 * @license         https://store.bird.eu/license/
 */
declare(strict_types=1);

namespace Blackbird\CatalogDefaultQty\Plugin\Model;

use Blackbird\CatalogDefaultQty\Model\Config;
use Magento\Catalog\Model\Product;
use Magento\Framework\DataObject;

/**
 * Product plugin which set the default qty to apply
 */
final class ProductPlugin
{
    /**
     * @var \Blackbird\CatalogDefaultQty\Model\Config
     */
    private $config;

    /**
     * @param \Blackbird\CatalogDefaultQty\Model\Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * Add the default qty to the product
     *
     * @param \Magento\Catalog\Model\Product $subject
     * @param \Magento\Framework\DataObject $result
     * @return \Magento\Framework\DataObject
     */
    public function afterGetPreconfiguredValues(Product $subject, DataObject $result): DataObject
    {
        $result->setData(
            'qty',
            $this->config->getDefaultQtyByProductType(
                $subject->getTypeId(),
                $subject->getStore()->getWebsite()->getCode()
            )
        );

        return $result;
    }
}
