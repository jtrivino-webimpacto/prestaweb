<?php

if (!defined('_PS_VERSION_'))
    exit;

class Infoproduct extends Module
{

    public function __construct()
    {
        $this->name = 'infoproduct';
        $this->author = 'Jeimy Triviño';
        $this->version = '1.0.0';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Extra Information in Product');
        $this->description = $this->l('This module show extra information in Front y Back');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install() {
        if (!parent::install() || !$this->_installSql() ||
        !$this->registerHook('displayAdminProductsMainStepLeftColumnMiddle') ||
        !$this->registerHook('displayProductAdditionalInfo')
        ) {
            return false;
        }

        return true;
    }

    public function uninstall() {
        return parent::uninstall();
    }

    protected function _installSql() {
        $sqlInstall = 'ALTER TABLE '._DB_PREFIX_.'product ADD custom_field VARCHAR(255) NULL';
        $sqlInstallLang = 'ALTER TABLE ' ._DB_PREFIX_. 'product_lang
                ADD custom_field_lang VARCHAR(255) NULL,
                ADD custom_field_lang_wysiwyg TEXT NULL';

        $returnSql = Db::getInstance()->execute($sqlInstall);
        $returnSqlLang = Db::getInstance()->execute($sqlInstallLang);

        return $returnSql && $returnSqlLang;
    }

    public function hookDisplayAdminProductsMainStepLeftColumnMiddle($params) {
        $product = new Product($params['id_product']);
        $languages = Language::getLanguages($active);
        $this->context->smarty->assign(array(
            'custom_field' => $product->custom_field,
            'languages' => $languages,
            'custom_field_lang' => $product->custom_field_lang,
            'custom_field_lang_wysiwyg' => $product->custom_field_lang_wysiwyg,
            'default_language' => $this->context->employee->id_lang,
            )
           );

        return $this->display(__FILE__, 'views/templates/hook/extrafields.tpl');
    }

    public function hookDisplayProductAdditionalInfo($params)
    {
        $product = new Product($params['id_product']);
        $this->context->smarty->assign(array(
            'custom_field' => $product->custom_field,
            'custom_field_lang' => $product->custom_field_lang,
            'custom_field_lang_wysiwyg' => $product->custom_field_lang_wysiwyg,
            )
           );

        return $this->display(__FILE__, 'views/templates/hook/product.tpl');
    }
}
