<?php

class infoemailClass extends ObjectModelCore
{
    public $id_coupon;
    public $user_id;
    public $firstname;
    public $lastname;
    public $email;
    public $coupon;
    public $date;

    public static $definition = array(
        'table' => 'coupon',
        'primary' => 'id_coupon',
        'multilang' => false,
        'fields' => array(
            'user_id' => array('type' => self::TYPE_INT, 'required' => true),
            'firstname' => array('type' => self::TYPE_STRING, 'required' => true),
            'lastname' => array('type' => self::TYPE_STRING, 'required' => true),
            'email' => array('type' => self::TYPE_STRING, 'required' => true),
            'coupon' => array('type' => self::TYPE_STRING, 'required' => true),
            'date' => array('type' => self::TYPE_DATE, 'required' => true),
        )
    );

}
