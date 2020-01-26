<?php

namespace KebaCorp\VaultSecret;

use Psr\SimpleCache\CacheInterface;
use SecretCache;

/**
 * Class VaultSecretParams.
 *
 * @package KebaCorp\VaultSecret
 */
class VaultSecretParams
{
    /**
     * Secrets cache.
     *
     * @var CacheInterface
     */
    private $_cache;

    /**
     * Is save template.
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
     * @return CacheInterface
     */
    public function getCache()
    {
        return $this->_cache;
    }

    /**
     * @param CacheInterface $cache
     */
    public function setCache($cache)
    {
        $this->_cache = $cache;
    }

    /**
     * @return bool
     */
    public function isIsSaveTemplate()
    {
        return $this->_isSaveTemplate;
    }

    /**
     * @param bool $isSaveTemplate
     */
    public function setIsSaveTemplate($isSaveTemplate)
    {
        $this->_isSaveTemplate = $isSaveTemplate;
    }

    /**
     * @return string
     */
    public function getSaveTemplateFilename()
    {
        return $this->_saveTemplateFilename;
    }

    /**
     * @param string $saveTemplateFilename
     */
    public function setSaveTemplateFilename($saveTemplateFilename)
    {
        $this->_saveTemplateFilename = $saveTemplateFilename;
    }
}
