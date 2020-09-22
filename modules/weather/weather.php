<?php

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

if (!defined('_PS_VERSION_'))
    exit;

class Weather extends Module implements WidgetInterface
{

    public function __construct()
    {
        $this->name = 'weather';
        $this->author = 'Jeimy Triviño';
        $this->version = '1.0.0';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Weather');
        $this->description = $this->l('This module show weather information');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install() &&
        $this->registerHook('displayNav1') &&
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

    public function getWidgetVariables($hookName, array $configuration)
    {
        //$zone = $this->context->country->name[Configuration::get('PS_LANG_DEFAULT')];
        $url = 'http://api.openweathermap.org/data/2.5/weather?q=Madrid&appid=0cc14a25e1dadfa6a127c8cf4e8200e7';
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($response);

        if($res->cod == 200) {

            return array(
                'temp' => $res->main->temp,
                'hum' => $res->main->humidity,
                'pres' => $res->main->pressure
            );
        }else {
            return false;
        }
    }

    public function renderWidget($hookName, array $configuration)
    {
        if($this->getWidgetVariables($hookName, $configuration)) {
            $this->context->smarty->assign($this->getWidgetVariables($hookName, $configuration));

            $template = '/views/templates/front/plantillaNav1.tpl';

            return $this->fetch('module:weather'.$template);
        }
    }
}
