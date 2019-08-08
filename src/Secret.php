<?php
/**
 * Created by Abek Narynov.
 * Date: 2019-08-08
 * @link https://github.com/KebaCorp
 * @copyright Copyright (c) 2018 KebaCorp
 */

namespace KebaCorp\VaultSecret;

/**
 * Class Secret.
 *
 * Singleton to load secret files.
 *
 * @package KebaCorp\VaultSecret
 */
class Secret
{
    /**
     * @var Secret
     */
    private static $instance;

    /**
     * Secrets DTO.
     *
     * @var SecretDTO
     */
    private $_secretDto;

    /**
     * Gets the instance via lazy initialization (created on first usage).
     *
     * @return Secret
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Secret constructor.
     *
     * Is not allowed to call from outside to prevent from creating multiple instances,
     * to use the singleton, you have to obtain the instance from Singleton::getInstance() instead.
     */
    private function __construct()
    {
        $this->_secretDto = new SecretDTO();
    }

    /**
     * Prevent the instance from being cloned (which would create a second instance of it).
     */
    private function __clone()
    {
    }

    /**
     * Prevent from being unserialized (which would create a second instance of it).
     */
    private function __wakeup()
    {
    }

    /**
     * @param string $secretFileName
     */
    public function load($secretFileName)
    {
        if (file_exists($secretFileName)) {
            $file = file_get_contents($secretFileName);

            $this->_secretDto->secrets = $file;
            print_r($this->_secretDto);
        }
    }
}
