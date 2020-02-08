<?php
/**
 * Created by Abek Narynov.
 * Date: 2019-08-08
 * @link https://github.com/KebaCorp
 * @copyright Copyright (c) 2018 KebaCorp
 */

namespace KebaCorp\VaultSecret;

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
     * Get secret by key from Vault service or from file.
     *
     * @param string $key
     * Secret key.
     *
     * @param string|null $source
     * Default Vault secrets data source.
     * If the source is null, then it will be taken from the VaultSecretParams.
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
     * @since 1.0.4
     */
    static public function getSecret($key, $source = null, $default = null, $type = TemplateCreator::TEMPLATE_KV2)
    {
        $secret = Secret::getInstance();

        return $secret->getSecret($key, $source, $default, $type);
    }
}
