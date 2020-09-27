<?php
/**
 * 2007-2019 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */


class Product extends ProductCore
{
    public $custom_field;
    public $custom_field_lang;
    public $custom_field_lang_wysiwyg;

    public function __construct($id_product = null, $full = false, $id_lang = null, $id_shop = null, Context $context = null) {

        self::$definition['fields']['custom_field'] = [
            'type' => self::TYPE_STRING,
            'required' => false,
            'size' => 255
        ];
        self::$definition['fields']['custom_field_lang'] = [
            'type' => self::TYPE_STRING,
            'lang' => true,
            'required' => false,
            'size' => 255
        ];
        self::$definition['fields']['custom_field_lang_wysiwyg'] = [
            'type' => self::TYPE_HTML,
            'lang' => true,
            'required' => false,
            'validate' => 'isCleanHtml'
        ];
        parent::__construct($id_product, $full, $id_lang, $id_shop, $context);
    }
}
