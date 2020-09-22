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
        $this->smarty->assign('save', false);

        if(Tools::isSubmit('submitInfo'))
        {
            $this->sendTestEmail(Tools::getValue('exampleMessage'));
            $this->smarty->assign('save', true);
        }

        return $this->display(__FILE__, 'configure.tpl');
    }

    public function generarVoucher(Customer $customer)
    {

        $cartRuleObj = new CartRule();

        $cartRuleObj->date_from = date('Y-m-d H:i:s');
        $cartRuleObj->date_to = '2046-12-12 00:00:00';
        $cartRuleObj->name[Configuration::get('PS_LANG_DEFAULT')] = 'Voucher';
        $cartRuleObj->quantity = 1;
        $code = Tools::passwdGen();
            while (CartRule::cartRuleExists($code)) {
        $code = Tools::passwdGen();
    }
        $cartRuleObj->code = $code;
        //dump($cartRuleObj->code);
        $cartRuleObj->quantity_per_user = 1;
        $cartRuleObj->reduction_percent = 20;
        $cartRuleObj->reduction_amount = 0;
        $cartRuleObj->free_shipping = 0;
        $cartRuleObj->active = 1;
        $cartRuleObj->minimum_amount = 0;
        $cartRuleObj->id_customer = $customer->id;
        $cartRuleObj->add();
    }

    public function sendTestEmail($email)
    {

        Mail::Send(
            $this->context->language->id,
            'test',
            $this->l('This is a test email'),
            array(
                '{datetime}' => date('Y-m-d H:i:s'),
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
