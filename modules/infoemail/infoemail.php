<?php

if (!defined('_PS_VERSION_'))
    exit;

require_once(_PS_MODULE_DIR_ . "infoemail/infoemailClass.php");

class Infoemail extends Module
{

    public function __construct()
    {
        $this->name = 'infoemail';
        $this->tab = 'administration';
        $this->author = 'Jeimy Triviño';
        $this->version = '1.0.0';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Email information to customer');
        $this->description = $this->l('This module send email to customer');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
            return parent::install() &&
            $this->registerHook('actionValidateOrder') &&
            Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'coupon` (
                `id_coupon` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `user_id` int(10) NOT NULL,
                `firstname` varchar(100) NOT NULL,
                `lastname` varchar(100) NOT NULL,
                `email` varchar(100) NOT NULL,
                `coupon` varchar(20) NOT NULL,
                `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id_coupon`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;');;
    }

    public function uninstall()
    {

        if (
            parent::uninstall()
            && Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'coupon`'))
            return true;
        return false;
    }

    public function getContent()
    {

    }

    public function hookActionValidateOrder($params)
    {

        $context = Context::getContext();
        $customer = $params['customer'];
        $order = $params['order'];
        $total = $order->total_paid;

        if ($total <= 30) {
            $subject = sprintf(Mail::l('Orden con referencia %d'), $params['order']->id);
		    $shop_email = Configuration::get('PS_SHOP_EMAIL');
		    $shop_name = Configuration::get('PS_SHOP_NAME');
		    $mail_dir = dirname(__FILE__).'/mails';
		    $mail_vars = array(
			    '{id_order}' => $order->id,
                '{firstname}' => $customer->firstname,
                '{lastname}' => $customer->lastname,
                '{email}' => $customer->email,
                '{total}' => $total
		    );

        Mail::Send(
            $this->context->language->id,
			'test',
			$subject,
			$mail_vars,
			$shop_email,
			null,
			$shop_email,
			$shop_name,
			null,
			null,
			$mail_dir,
			false,
			$this->context->shop->id
        );
        } else {
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

            $cartRuleObj->quantity_per_user = 1;
            $cartRuleObj->reduction_percent = 20;
            $cartRuleObj->reduction_amount = 0;
            $cartRuleObj->free_shipping = 0;
            $cartRuleObj->active = 1;
            $cartRuleObj->minimum_amount = 0;
            $cartRuleObj->id_customer = $customer->id;
            $cartRuleObj->add();

            $subject = sprintf(Mail::l('Orden con referencia %d'), $params['order']->id);
		    $shop_email = Configuration::get('PS_SHOP_EMAIL');
		    $shop_name = Configuration::get('PS_SHOP_NAME');
		    $mail_dir = dirname(__FILE__).'/mails';
		    $mail_vars = array(
			    '{id_order}' => $order->id,
                '{firstname}' => $customer->firstname,
                '{lastname}' => $customer->lastname,
                '{email}' => $customer->email,
                '{coupon}' => $code
		    );



        Mail::Send(
            $this->context->language->id,
			'voucher',
			$subject,
			$mail_vars,
			$shop_email,
			null,
			$shop_email,
			$shop_name,
			null,
			null,
			$mail_dir,
			false,
			$this->context->shop->id
		);
        }

    }
}
