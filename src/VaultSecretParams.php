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
     * Default Vault url to secrets.
     *
     * @var string
     * @since 2.0.0
     */
    private $_url;

    /**
     * Default path to json file with Vault secrets.
     *
     * @var string
     * @since 2.0.0
     */
    private $_file;

    /**
     * Secrets cache.
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
        $this->_cache = SecretCache::getInstance();
    }

    /**
     * Get default Vault url to secrets.
     *
     * @return string
     * @since 2.0.0
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * Set default Vault url to secrets.
     *
     * @param string $url
     * @since 2.0.0
     */
    public function setUrl($url)
    {
        $this->_url = $url;
    }

    /**
     * Get default path to json file with Vault secrets.
     *
     * @return string
     * @since 2.0.0
     */
    public function getFile()
    {
        return $this->_file;
    }

    /**
     * Set default path to json file with Vault secrets.
     *
     * @param string $file
     * @since 2.0.0
     */
    public function setFile($file)
    {
        $this->_file = $file;
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
        $this->_isSaveTemplate = $isSaveTemplate;
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
     * @since 2.0.0
     */
    public function setSaveTemplateFilename($saveTemplateFilename)
    {
        $this->_saveTemplateFilename = $saveTemplateFilename;
    }
}
