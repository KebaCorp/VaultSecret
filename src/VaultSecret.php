<?php
/**
 * Created by Abek Narynov.
 * Date: 2019-08-08
 * @link https://github.com/KebaCorp
 * @copyright Copyright (c) 2018 KebaCorp
 */

namespace KebaCorp\VaultSecret;

use Exception;
use KebaCorp\VaultSecret\template\TemplateCreator;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class VaultSecret.
 *
 * The extension allows to load the Vault secrets from json files and get them.
 * Read more about the component in README.md
 *
 * @package KebaCorp\VaultSecret
 * @since 1.0.0
 */
class VaultSecret
{
    /**
     * Set VaultSecret params object.
     *
     * @param VaultSecretParams $vaultSecretParams
     * @since 2.0.0
     */
    static public function setParams(VaultSecretParams $vaultSecretParams)
    {
        $secret = Secret::getInstance();

        $secret->setVaultSecretParams($vaultSecretParams);
    }

    /**
     * Returns secret by key from Vault service.
     *
     * @param string $key
     * Secret key.
     *
     * @param string|null $url
     * Default Vault url to secrets.
     * If the url is null, then the url will be taken from the VaultSecretParams.
     *
     * @param mixed|null $default
     * Returns the default value if the secret was not found.
     *
     * @param int $type
     * Vault data parser template type.
     *
     * @return mixed|null
     * Returns null if no secrets were found.
     *
     * @throws InvalidArgumentException
     *
     * @throws Exception
     *
     * @since 1.0.4
     */
    static public function getSecret($key, $url = null, $default = null, $type = TemplateCreator::TEMPLATE_KV2)
    {
        $secret = Secret::getInstance();

        return $secret->getSecret($key, $url, $default, $type);
    }

    /**
     * Returns secret by key from json file.
     *
     * @param string $key
     * Secret key.
     *
     * @param string|null $file
     * Default path to json file with Vault secrets.
     * If the filename is null, then the filename will be taken from the VaultSecretParams.
     *
     * @param mixed|null $default
     * Returns the default value if the secret was not found.
     *
     * @param int $type
     * Vault data parser template type.
     *
     * @return mixed|null
     * Returns null if no secrets were found.
     *
     * @throws InvalidArgumentException
     *
     * @since 2.0.0
     */
    static public function getSecretFromJsonFile(
        $key,
        $file = null,
        $default = null,
        $type = TemplateCreator::TEMPLATE_KV2
    ) {
        $secret = Secret::getInstance();

        return $secret->getSecretFromJsonFile($key, $file, $default, $type);
    }
}
