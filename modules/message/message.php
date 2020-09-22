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
        $this->description = 'Welcome message to my shop';
    }

    public function install()
    {
        if(!parent::install() ||
           !Configuration::updateValue('WEBIMPACTO_MESSAGE', 'Welcome to my shop with PrestaShop') ||
           !$this->registerHook('displayHome') ||
           !$this->registerHook('displayFooterBefore')
            )
            return false;
        return true;
    }

    public function uninstall()
    {
        if(!parent::uninstall() ||
           !Configuration::deleteByName('WEBIMPACTO_MESSAGE')
           )
           return false;

        return true;
    }

    public function hookDisplayHome($params)
    {
        $message = Configuration::get('WEBIMPACTO_MESSAGE');
        $this->context->smarty->assign('message', $message);

        return $this->display(__FILE__, 'displayHome.tpl');
    }

    public function hookDisplayFooterBefore($params)
    {
        $message = Configuration::get('WEBIMPACTO_MESSAGE');
        $this->context->smarty->assign('message', $message);

        return $this->display(__FILE__, 'displayFooterBefore.tpl');
    }
}
