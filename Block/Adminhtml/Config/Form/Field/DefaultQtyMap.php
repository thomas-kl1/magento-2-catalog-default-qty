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
 * @license         MIT
 * @support         https://github.com/blackbird-agency/magento-2-catalog-default-qty/issues/new
 */
declare(strict_types=1);

namespace Blackbird\CatalogDefaultQty\Block\Adminhtml\Config\Form\Field;

use Blackbird\CatalogDefaultQty\Block\Adminhtml\Config\Form\Field\Select\ProductTypes;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Phrase;

/**
 * Field catalog product default quantity mapper
 */
class DefaultQtyMap extends AbstractFieldArray
{
    /**
     * Retrieve the product type select renderer
     *
     * @return \Blackbird\CatalogDefaultQty\Block\Adminhtml\Config\Form\Field\Select\ProductTypes
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductTypeSelectRenderer(): ProductTypes
    {
        if (!$this->hasData('product_type_select_renderer')) {
            $this->setData(
                'product_type_select_renderer',
                $this->getLayout()->createBlock(
                    ProductTypes::class,
                    '',
                    ['data' => ['is_render_to_js_template' => true]]
                )
            );
        }

        return $this->getData('product_type_select_renderer');
    }

    /**
     * @inheritdoc
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareToRender(): void
    {
        $this->addColumn(
            'product_type',
            [
                'label' => new Phrase('Product Type'),
                'class' => 'product_type',
                'renderer' => $this->getProductTypeSelectRenderer(),
            ]
        );
        $this->addColumn(
            'default_qty',
            [
                'label' => new Phrase('Default Qty'),
                'class' => 'default_qty validate-digits validate-number validate-zero-or-greater',
            ]
        );
        $this->_addAfter = false;
        $this->_addButtonLabel = (new Phrase('Add Storefront Default Qty'))->render();
    }

    /**
     * @inheritdoc
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $rowHash = $this->getProductTypeSelectRenderer()->calcOptionHash($row->getData('product_type'));
        $row->setData('option_extra_attrs', ['option_' . $rowHash => 'selected="selected"']);
    }
}
