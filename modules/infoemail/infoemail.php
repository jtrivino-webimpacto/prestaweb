<?php

if (!defined('_PS_VERSION_'))
    exit;

require_once 'Helpers.php';

class Infoemail extends Module
{
    use Helpers;

    public function __construct()
    {
        $this->name = 'infoemail';
        $this->tab = 'emailing';
        $this->author = 'Jeimy Triviño';
        $this->version = '1.0.0';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Email information to customer');
        $this->description = $this->l('This module send email to customer');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install() && $this->installDb() && $this->registerHook('actionValidateOrder');

    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallDb();
    }

    public function getContent()
    {
        if(Tools::isSubmit('submitInfo'))
        {
            $this->sendTestEmail(Tools::getValue('exampleMessage'));
        }

        return $this->display(__FILE__, 'configure.tpl');
    }

    public function sendTestEmail($email)
    {
        Mail::Send(
            $this->context->language->id,
            'test',
            $this->l('This is a test email'),
            array(
                '{datetime}' => date('Y-m-d H:i:s')
            ),
            $email,
            'PrestaShop User',
            Configuration::get('PS_SHOP_EMAIL'),
            Configuration::get('PS_SHOP_NAME'),
            null,
            null,
            dirname(__FILE__).'/mails'
        );
    }

    public function hookActionValidateOrder($params)

    {

        $details = $params['order'];



        echo "<pre>";

        print_r($details);

        echo "<pre>";

    }
}
