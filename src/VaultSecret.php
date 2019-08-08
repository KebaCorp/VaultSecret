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
     * @param string $secretFileName
     */
    static public function load($secretFileName)
    {
        $secret = Secret::getInstance();
        $secret->load($secretFileName);
    }
}
