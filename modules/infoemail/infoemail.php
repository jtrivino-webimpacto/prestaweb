<?php
/**
* 2007-2020 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2020 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Infoemail extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'infoemail';
        $this->tab = 'emailing';
        $this->version = '1.0.0';
        $this->author = 'Jeimy Triviño';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Cupon Email');
        $this->description = $this->l('Este modulo envia informacion por email al cliente');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('INFOEMAIL_LIVE_MODE', false);

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('actionValidateOrder');
    }

    public function uninstall()
    {
        Configuration::deleteByName('INFOEMAIL_LIVE_MODE');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        $sql="
        SELECT cu.id_customer, cu.firstname, cu.lastname,cu.email, ru.code, ru.date_add
            FROM
            "._DB_PREFIX_."cart_rule ru
            LEFT JOIN "._DB_PREFIX_."customer cu ON ru.id_customer = cu.id_customer
            WHERE ru.description ='Cupon_especial'
        ";
        $result = Db::getInstance()->ExecuteS($sql);

        $this->context->smarty->assign([
            'datos' => $result
        ]);

        if (((bool)Tools::isSubmit('submitInfoemailModule')) == true) {
            $this->postProcess();
            $output.=$this->displayConfirmation($this->l('save succesfully'));
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output .= $this->context->smarty->fetch($this->local_path.'views/templates/admin/couponEmail.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitInfoemailModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-flag"></i>',
                        'desc' => $this->l('Introduzca cantidad de dinero'),
                        'name' => 'INFOEMAIL_MONTO',
                        'label' => $this->l('Cantidad de dinero (€)'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-flag"></i>',
                        'desc' => $this->l('Introduzca importe del cupón'),
                        'name' => 'INFOEMAIL__DESCUENTO',
                        'label' => $this->l('Importe Cupón (€)'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'INFOEMAIL_MONTO' => Configuration::get('INFOEMAIL_MONTO', 'cantidad dinero'),
            'INFOEMAIL__DESCUENTO' => Configuration::get('INFOEMAIL__DESCUENTO', 'phrase before footer'),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function hookactionValidateOrder($params)
    {
        $order = $params['order'];
        $customer = $params['customer'];
        $tiene_cupon = 'no';


        $sql="
         SELECT sum(total_paid) as total_spend FROM "._DB_PREFIX_."orders WHERE id_customer='".$customer->id ."';
        ";
        $db = Db::getInstance()->getRow($sql);
        if (($db = Db::getInstance()->getRow($sql)) != 0) {
            $cantidad = $db['total_spend'];
        } else {
            $cantidad = 0;
        }

        
        $cantidad_para_cupon = Configuration::get('INFOEMAIL_MONTO');
        $importe_para_cupon = Configuration::get('INFOEMAIL_DESCUENTO');
        $codigo_cupon = Tools::substr(md5(time()), 0, 8);
        $now = date('Y-m-d H:i:s');
        $to = date('2021-m-d H:i:s');

        $sql="
         SELECT * FROM "._DB_PREFIX_."cart_rule WHERE id_customer = '".$customer->id ."'
        ";

        $db = Db::getInstance()->getRow($sql);
        if (($db = Db::getInstance()->getRow($sql)) == 0) {
            if ($cantidad >= $cantidad_para_cupon) {
                
                $sql = 'INSERT INTO '._DB_PREFIX_.'cart_rule (id_customer, date_from, date_to, description, quantity,
                 quantity_per_user, code, reduction_amount, active, reduction_tax, date_add)
                 VALUES ("'.$customer->id.'", "'.$now.'", "'.$to.'", "Cupon_especial", "1", "1", "'.$codigo_cupon.'",
                  "'.$importe_para_cupon.'", "1", "1","'.$now.'")';
                Db::getInstance()->execute($sql);
                $id_cupon = Db::getInstance()->Insert_ID();

                $sql = 'INSERT INTO '._DB_PREFIX_.'cart_rule_lang (id_cart_rule, id_lang, name)
                 VALUES ("'.$id_cupon.'","1", "Cupon_especial")';
                Db::getInstance()->execute($sql);
                $tiene_cupon = 'si';
            }
        } else {
                $tiene_cupon = 'si';
                $codigo_cupon = $db['code'];
                $importe_para_cupon = $db['reduction_amount'];
        }

        $enviar_cupon = '';
        if ($tiene_cupon == 'si') {
            $enviar_cupon.= '<br>Se le ha generado un cupon de descuento:';
            $enviar_cupon.= '<br>Código: '.$codigo_cupon;
            $enviar_cupon.= '<br>Importe: '.Context::getContext()->
                currentLocale->formatPrice($importe_para_cupon, 'EUR');
        }

        $templateVars = array(
            '{firstname}' => $customer->firstname,
            '{lastname}' => $customer->lastname,
            '{cantidad_dinero_gastado}' => Context::getContext()->currentLocale->formatPrice($cantidad, 'EUR'),
            '{enviar_cupon}' => $enviar_cupon
        );
      
        Mail::Send(
            (int) $order->id_lang,
            'voucher_new2',
            Mail::l('Gracias por su compra', (int) $order->id_lang),
            $templateVars,
            $customer->email,
            $customer->firstname . ' ' .$customer->lastname,
            null,
            null,
            null,
            null,
            _PS_MAIL_DIR_,
            true,
            (int) $order->id_shop
        );
    }
}

