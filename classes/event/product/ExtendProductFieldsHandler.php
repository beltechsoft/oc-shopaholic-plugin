<?php namespace Lovata\Shopaholic\Classes\Event\Product;

use Lovata\Toolbox\Classes\Event\AbstractBackendFieldHandler;

use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Models\Settings;
use Lovata\Shopaholic\Controllers\Products;

/**
 * Class ExtendProductFieldsHandler
 * Price fields of single offer in product form (setting "single_offer_mode")
 * @package Lovata\Shopaholic\Classes\Event\Product
 */
class ExtendProductFieldsHandler extends AbstractBackendFieldHandler
{
    const TAB = 'lovata.toolbox::lang.tab.settings';

    /**
     * Extend form fields
     * @param \Backend\Widgets\Form $obWidget
     */
    protected function extendFields($obWidget)
    {
        if (!Settings::getValue('single_offer_mode')) {
            return;
        }

        $iOfferCount = $obWidget->model->exists ? $obWidget->model->offer()->count() : 0;

        $obWidget->removeField('offer');
        if ($iOfferCount > 1) {
            $obWidget->addTabFields([
                'offer' => [
                    'label'   => 'lovata.shopaholic::lang.field.offer',
                    'span'    => 'full',
                    'context' => ['update', 'preview'],
                    'type'    => 'partial',
                    'path'    => '~/plugins/lovata/shopaholic/controllers/products/_offer.htm',
                    'tab'     => self::TAB,
                ],
            ]);

            return;
        }

        $obWidget->addTabFields([
            'single_offer_section'   => [
                'label' => 'lovata.shopaholic::lang.field.single_offer_section',
                'type'  => 'section',
                'tab'   => self::TAB,
            ],
            'single_offer_price'     => [
                'label' => 'lovata.shopaholic::lang.field.price',
                'type'  => 'number',
                'step'  => 'any',
                'span'  => 'left',
                'tab'   => self::TAB,
            ],
            'single_offer_old_price' => [
                'label' => 'lovata.shopaholic::lang.field.old_price',
                'type'  => 'number',
                'step'  => 'any',
                'span'  => 'right',
                'tab'   => self::TAB,
            ],
            'single_offer_quantity'  => [
                'label' => 'lovata.shopaholic::lang.field.quantity',
                'type'  => 'number',
                'span'  => 'left',
                'tab'   => self::TAB,
            ],
            'single_offer_add'       => [
                'type' => 'partial',
                'path' => '~/plugins/lovata/shopaholic/controllers/products/_single_offer_add.htm',
                'span' => 'full',
                'tab'  => self::TAB,
            ],
        ]);
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass() : string
    {
        return Product::class;
    }

    /**
     * Get controller class name
     * @return string
     */
    protected function getControllerClass() : string
    {
        return Products::class;
    }
}
