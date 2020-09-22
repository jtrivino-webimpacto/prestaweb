<?php

trait Helpers
{
    /**
     * This method installs the database.
     * Creates a table called coupon_data
     * with an user_id, firstname, lastname, email, coupon and date using
     * the mysql engine and the utf8 charset.
     *
     * @return boolean
     */
    protected function installDb()
    {
        $return = true;

        $return &= Db::getInstance()->execute('
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'coupon_data` (
                `user_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `firstname` VARCHAR(100) NOT NULL,
                `lastname` VARCHAR(100) NOT NULL,
                `email` VARCHAR(20) NOT NULL,
                `coupon` VARCHAR(50) NOT NULL,
                `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`user_id`)
                ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
    }

    /**
     * This method uninstalls the database.
     * Deletes a table called coupon_data.
     *
     * @return boolean
     */
    protected function uninstallDb()
    {
        if (
            parent::uninstall()
            && Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'coupon_data`'))
            return true;
        return false;
    }
}
