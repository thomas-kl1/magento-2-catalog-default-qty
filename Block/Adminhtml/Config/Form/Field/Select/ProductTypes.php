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

namespace Blackbird\CatalogDefaultQty\Block\Adminhtml\Config\Form\Field\Select;

use Magento\Catalog\Api\ProductTypeListInterface;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;

/**
 * Product type select html element
 */
class ProductTypes extends Select
{
    /**
     * @var \Magento\Catalog\Api\ProductTypeListInterface
     */
    private $productTypeList;

    /**
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Magento\Catalog\Api\ProductTypeListInterface $productTypeList
     * @param array $data
     */
    public function __construct(
        Context $context,
        ProductTypeListInterface $productTypeList,
        array $data = []
    ) {
        $this->productTypeList = $productTypeList;
        parent::__construct($context, $data);
    }

    /**
     * Set the input name
     *
     * @param string $value
     * @return $this
     */
    public function setInputName($value): self
    {
        return $this->setData('name', $value);
    }

    /**
     * @inheritdoc
     */
    protected function _toHtml(): string
    {
        if (!$this->getOptions()) {
            foreach ($this->productTypeList->getProductTypes() as $productType) {
                $this->addOption($productType->getName(), $productType->getLabel());
            }
        }

        return parent::_toHtml();
    }
}
