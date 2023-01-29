<?php

namespace core;

if (!defined('PRFK_SITE_ROOT_DIR')) {
  die();
}

/**
 * Singleton
 *
 * Паттерн "Одиночка"
 * @see http://dron.by/post/patterny-shablony-proektirovanie-v-php-vvedenie.html
 * @package core
 */
trait Singleton
{

    static private $instance = null;

    /**
     * @return Singleton
     */
    private function __construct()
    {
        //защищаем от создания через new Singleton
    }

    /**
     * @return Singleton
     */
    private function __clone()
    {
        //защищаем от создания через клонирование
    }

    /**
     * @return Singleton
     */
    private function __wakeup()
    {
        //защищаем от создания через unserialize
    }

    /**
     * @return Singleton
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new static(); //new self()
        }
        return self::$instance;
    }
}
