<?php

namespace KebaCorp\VaultSecret;

use Psr\SimpleCache\CacheInterface;

/**
 * Class VaultSecretParams.
 *
 * @package KebaCorp\VaultSecret
 */
class VaultSecretParams
{
    /**
     * Default Vault url to secrets.
     *
     * @var string
     */
    private $_url;

    /**
     * Default path to json file with Vault secrets.
     *
     * @var string
     */
    private $_file;

    /**
     * Secrets cache.
     *
     * @var CacheInterface
     */
    private $_cache;

    /**
     * Vault token.
     *
     * @var string
     */
    private $_token;

    /**
     * Is save template to file.
     *
     * @var bool
     */
    private $_isSaveTemplate = true;

    /**
     * Save template's filename.
     *
     * @var string
     */
    private $_saveTemplateFilename = 'secretsTemplate.json';

    /**
     * VaultSecretParams constructor.
     */
    public function __construct()
    {
        $this->_cache = SecretCache::getInstance();
    }

    /**
     * Get default Vault url to secrets.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * Set default Vault url to secrets.
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->_url = $url;
    }

    /**
     * Get path to json file with Vault secrets.
     *
     * @return string
     */
    public function getFile()
    {
        return $this->_file;
    }

    /**
     * Set path to json file with Vault secrets.
     *
     * @param string $file
     */
    public function setFile($file)
    {
        $this->_file = $file;
    }

    /**
     * Return secrets cache.
     *
     * @return CacheInterface
     */
    public function getCache()
    {
        return $this->_cache;
    }

    /**
     * Set secrets cache.
     *
     * @param CacheInterface $cache
     */
    public function setCache($cache)
    {
        $this->_cache = $cache;
    }

    /**
     * Returns Vault token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * Set Vault token.
     *
     * @param string $token
     */
    public function setToken($token)
    {
        $this->_token = $token;
    }

    /**
     * Is save template to file.
     *
     * @return bool
     */
    public function isIsSaveTemplate()
    {
        return $this->_isSaveTemplate;
    }

    /**
     * Set is save template to a file.
     *
     * @param bool $isSaveTemplate
     */
    public function setIsSaveTemplate($isSaveTemplate)
    {
        $this->_isSaveTemplate = $isSaveTemplate;
    }

    /**
     * Get saved template's filename without extension.
     *
     * @return string
     */
    public function getSaveTemplateFilename()
    {
        return $this->_saveTemplateFilename;
    }

    /**
     * Set template's filename without extension to save to a file.
     *
     * @param string $saveTemplateFilename
     */
    public function setSaveTemplateFilename($saveTemplateFilename)
    {
        $this->_saveTemplateFilename = $saveTemplateFilename;
    }
}
