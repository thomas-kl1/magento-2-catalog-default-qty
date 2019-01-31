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

namespace Blackbird\CatalogDefaultQty\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Module configuration settings container
 * @api
 */
final class Config
{
    /**#@+
     * Scope Config: Data Settings Paths
     */
    public const CONFIG_PATH_DEFAULT_QTY_MAP = 'cataloginventory/options/default_qty';
    /**#@-*/

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private $serializer;

    /**
     * @var array
     */
    private $mappers;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        SerializerInterface $serializer
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->serializer = $serializer;
        $this->mappers = [];
    }

    /**
     * Retrieve the default qty mapper
     *
     * @param string|null $scopeCode
     * @return array
     */
    public function getDefaultQtyMap(?string $scopeCode = null): array
    {
        $scope = $scopeCode ?? 'current_scope';

        if (!isset($this->mappers[$scope])) {
            $mapper = [];
            $rawData = $this->serializer->unserialize(
                $this->scopeConfig->getValue(
                    self::CONFIG_PATH_DEFAULT_QTY_MAP,
                    ScopeInterface::SCOPE_WEBSITE,
                    $scopeCode
                ) ?? '{}'
            );

            foreach ($rawData as $data) {
                $mapper[$data['product_type']] = $data['default_qty'];
            }

            $this->mappers[$scope] = $mapper;
        }

        return $this->mappers[$scope];
    }

    /**
     * Retrieve the default qty by product type
     *
     * @param string $productType
     * @param string|null $scopeCode
     * @return float
     */
    public function getDefaultQtyByProductType(string $productType, ?string $scopeCode = null): float
    {
        return (float) ($this->getDefaultQtyMap($scopeCode)[$productType] ?? .0);
    }
}
