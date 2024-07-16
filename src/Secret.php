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
 * Class Secret.
 *
 * Singleton to load secret files.
 *
 * @package KebaCorp\VaultSecret
 * @since 1.0.3
 */
class Secret
{
    /**
     * Secret instance.
     *
     * @var Secret
     * @since 1.0.3
     */
    private static $instance;

    /**
     * Vault secret params.
     *
     * @var VaultSecretParams
     * @since 2.0.0
     */
    private $_vaultSecretParams;

    /**
     * Gets the instance via lazy initialization (created on first usage).
     *
     * @return Secret
     * @since 1.0.3
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
     *
     * @since 1.0.3
     */
    private function __construct()
    {
        $this->_vaultSecretParams = new VaultSecretParams();
    }

    /**
     * Prevent the instance from being cloned (which would create a second instance of it).
     *
     * @since 1.0.3
     */
    private function __clone()
    {
    }

    /**
     * Prevent from being unserialized (which would create a second instance of it).
     *
     * @since 1.0.3
     */
    public function __wakeup()
    {
    }

    /**
     * Get VaultSecret params object.
     *
     * @return VaultSecretParams
     * @since 2.0.0
     */
    public function getVaultSecretParams()
    {
        return $this->_vaultSecretParams;
    }

    /**
     * Set VaultSecret params object.
     *
     * @param VaultSecretParams $vaultSecretParams
     * @since 2.0.0
     */
    public function setVaultSecretParams($vaultSecretParams)
    {
        $this->_vaultSecretParams = $vaultSecretParams;
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
    public function getSecret(string $key, string $source, $default = null, $type = VaultSecret::TEMPLATE_TYPE_KV2)
    {
        $sourceKey = base64_encode($source);

        $secret = null;
        $cache = $this->_vaultSecretParams->getCache();
        $template = TemplateCreator::createTemplate($type);

        // If the source is null, then it will be taken from the VaultSecretParams
        if ($source === null) {
            $source = $this->_vaultSecretParams->getSource();
        }

        if ($cache->has($sourceKey)) {
            $template->setData($cache->get($sourceKey));
            $secret = $template->getSecret($key);
        } else {
            // Load data from source
            $options = array(
                'http' => array(
                    'method' => "GET",
                    'header' => "Authorization: Bearer {$this->_vaultSecretParams->getToken()}\r\n" .
                        "Content-Type: application/json\r\n",
                )
            );
            $context = stream_context_create($options);
            if ($content = file_get_contents($source, false, $context)) {
                if (($data = json_decode($content, true)) && is_array($data) && $cache->set($sourceKey, $data)) {
                    $template->setData($data);
                    $secret = $template->getSecret($key);
                }
            }
        }

        // Save template to file
        if ($this->_vaultSecretParams->isIsSaveTemplate()) {
            $template->saveTemplateToFile(
                $this->_vaultSecretParams->getSaveTemplateFilename()
                . '_' . $sourceKey . '.json'
            );
        }

        return $secret === null ? $default : $secret;
    }
}
