<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Message extends Module
{
    public function __construct()
    {
        $this->name = 'message';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Jeimy Triviño';
        $this->bootstrap = true;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        parent::__construct();
        $this->displayName = 'Welcome';
        $this->description = 'This module show Welcome message to my shop';
    }

    public function install()
    {
        return parent::install() &&
        $this->registerHook('displayHome') &&
        $this->registerHook('displayFooterBefore') &&
        $this->registerHook('header');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookHeader()
    {
        $this->context->controller->addCSS(array(
            $this->_path.'views/css/message.css'
        ));
    }

    public function getContent()
    {
        if(Tools::isSubmit('savemultipurposesting'))
        {
            $name = Tools::getValue('print');
            Configuration::updateValue('MULTIPURPOSE_STR', $name);
        }
        $this->context->smarty->assign(array(
            'MULTIPURPOSE_STR' => Configuration::get('MULTIPURPOSE_STR')
        ));
        return $this->display(__FILE__,'views/templates/admin/configure.tpl');
    }

    public function hookDisplayHome($params)
    {
        $this->context->smarty->assign(array(
            'MULTIPURPOSE_STR' => Configuration::get('MULTIPURPOSE_STR')
        ));
        return $this->display(__FILE__, 'message.tpl');
    }

    public function hookDisplayFooterBefore($params)
    {
        $this->context->smarty->assign(array(
            'MULTIPURPOSE_STR' => Configuration::get('MULTIPURPOSE_STR')
        ));
        return $this->display(__FILE__, 'message.tpl');
    }
}
