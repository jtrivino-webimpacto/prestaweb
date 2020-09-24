<?php

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

if (!defined('_PS_VERSION_'))
    exit;

class Infoproduct extends Module implements WidgetInterface
{

    public function __construct()
    {
        $this->name = 'infoproduct';
        $this->author = 'Jeimy Triviño';
        $this->version = '1.0.0';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('New message in the product');
        $this->description = $this->l('This module show additional information on the product');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install() &&
        $this->registerHook('displayProductAdditionalInfo') &&
        $this->registerHook('header');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookHeader()
    {
        $this->context->controller->addJs($this->_path.'/views/js/font.js');
        $this->context->controller->addCss($this->_path . 'views/css/font.css');
    }

    public function getContent()
    {

    }

    public function getWidgetVariables($hookName, array $configuration)
    {
        return "Prueba";
    }

    public function renderWidget($hookName, array $configuration)
    {
        if($this->getWidgetVariables($hookName, $configuration)) {
            $this->context->smarty->assign($this->getWidgetVariables($hookName, $configuration));

            $template = '/views/templates/front/plantillaProductAdditionalInfo.tpl';

            return $this->fetch('module:infoproduct'.$template);
        }
    }
}
