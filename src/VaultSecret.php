<?php
/**
 * Created by Abek Narynov.
 * Date: 2019-08-08
 * @link https://github.com/KebaCorp
 * @copyright Copyright (c) 2018 KebaCorp
 */

namespace KebaCorp\VaultSecret;

/**
 * Class VaultSecret.
 *
 * The extension allows to load the Vault secrets from json files and get them.
 * Read more about the component in README.md
 *
 * @package KebaCorp\VaultSecret
 */
class VaultSecret
{
    /**
     * Loads json secret file.
     *
     * @param string $secretFileName
     * @param array $options
     * @return bool
     */
    static public function load($secretFileName, $options = array())
    {
        $secret = Secret::getInstance();
        return $secret->load($secretFileName, $options);
    }

    /**
     * Returns secret by key.
     *
     * @param $key
     * @param string $default
     * @return string
     */
    static public function getSecret($key, $default = '')
    {
        $secret = Secret::getInstance();
        return $secret->getSecret($key, $default);
    }

    /**
     * Returns secret DTO.
     *
     * @return SecretDTO
     */
    static public function getSecretDto()
    {
        $secret = Secret::getInstance();
        return $secret->getSecretDto();
    }
}
