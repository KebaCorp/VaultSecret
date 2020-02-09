<?php

namespace KebaCorp\VaultSecret;

use Psr\SimpleCache\CacheInterface;

/**
 * Class VaultSecretParams.
 *
 * @package KebaCorp\VaultSecret
 * @since 2.0.0
 */
class VaultSecretParams
{
    /**
     * Default Vault secrets data source.
     *
     * This may be a link to the Vault service.
     * For example: 'http://localhost:8200/v1/kv2/data/secretName'.
     *
     * Or it could be the path to the json file.
     * For example: 'path/secret.json'.
     *
     * @var string
     * @since 2.0.0
     */
    private $_source;

    /**
     * Secrets cache.
     * SecretHybridCache is used by default.
     *
     * @var CacheInterface
     * @since 2.0.0
     */
    private $_cache;

    /**
     * Vault token.
     *
     * @var string
     * @since 2.0.0
     */
    private $_token;

    /**
     * Is save template to file.
     *
     * @var bool
     * @since 2.0.0
     */
    private $_isSaveTemplate = true;

    /**
     * Save template's filename.
     *
     * @var string
     * @since 2.0.0
     */
    private $_saveTemplateFilename = 'secretsTemplate.json';

    /**
     * VaultSecretParams constructor.
     *
     * @since 2.0.0
     */
    public function __construct()
    {
        // Set SecretHybridCache as default cache
        $this->_cache = SecretHybridCache::getInstance();
    }

    /**
     * Get default Vault secrets data source.
     *
     * This may be a link to the Vault service.
     * For example: 'http://localhost:8200/v1/kv2/data/secretName'.
     *
     * Or it could be the path to the json file.
     * For example: 'path/secret.json'.
     *
     * @return string
     * @since 2.0.0
     */
    public function getSource()
    {
        return $this->_source;
    }

    /**
     * Set default Vault secrets data source.
     *
     * This may be a link to the Vault service.
     * For example: 'http://localhost:8200/v1/kv2/data/secretName'.
     *
     * Or it could be the path to the json file.
     * For example: 'path/secret.json'.
     *
     * @param string $source
     * @since 2.0.0
     */
    public function setSource($source)
    {
        $this->_source = $source;
    }

    /**
     * Return secrets cache.
     *
     * @return CacheInterface
     * @since 2.0.0
     */
    public function getCache()
    {
        return $this->_cache;
    }

    /**
     * Set secrets cache.
     *
     * @param CacheInterface $cache
     * @since 2.0.0
     */
    public function setCache($cache)
    {
        $this->_cache = $cache;
    }

    /**
     * Returns Vault token.
     *
     * @return string
     * @since 2.0.0
     */
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * Set Vault token.
     *
     * @param string $token
     * @since 2.0.0
     */
    public function setToken($token)
    {
        $this->_token = $token;
    }

    /**
     * Is save template to file.
     *
     * @return bool
     * @since 2.0.0
     */
    public function isIsSaveTemplate()
    {
        return $this->_isSaveTemplate;
    }

    /**
     * Set is save template to a file.
     *
     * @param bool $isSaveTemplate
     * @since 2.0.0
     */
    public function setIsSaveTemplate($isSaveTemplate)
    {
        $this->_isSaveTemplate = (bool)$isSaveTemplate;
    }

    /**
     * Get saved template's filename without extension.
     *
     * @return string
     * @since 2.0.0
     */
    public function getSaveTemplateFilename()
    {
        return $this->_saveTemplateFilename;
    }

    /**
     * Set template's filename without extension to save to a file.
     *
     * @param string $saveTemplateFilename
     * @return bool
     * @since 2.0.0
     */
    public function setSaveTemplateFilename($saveTemplateFilename)
    {
        if (is_string($saveTemplateFilename)) {
            $this->_saveTemplateFilename = $saveTemplateFilename;

            return true;
        }

        return false;
    }
}
