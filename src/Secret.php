<?php
/**
 * Created by Abek Narynov.
 * Date: 2019-08-08
 * @link https://github.com/KebaCorp
 * @copyright Copyright (c) 2018 KebaCorp
 */

namespace KebaCorp\VaultSecret;

use Exception;
use KebaCorp\VaultSecret\template\TemplateAbstract;
use KebaCorp\VaultSecret\template\TemplateCreator;
use Psr\SimpleCache\InvalidArgumentException;
use SecretCache;

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
     * Secret instance.
     *
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
     * Template.
     *
     * @var TemplateAbstract
     */
    private $_template;

    /**
     * Vault secret params.
     *
     * @var VaultSecretParams
     */
    private $_vaultSecretParams;

    /**
     * Gets the instance via lazy initialization (created on first usage).
     *
     * @param int $templateType
     * @return Secret
     */
    public static function getInstance($templateType = TemplateCreator::TEMPLATE_KV2)
    {
        if (null === static::$instance) {
            static::$instance = new static($templateType);
        }

        return static::$instance;
    }

    /**
     * Secret constructor.
     *
     * Is not allowed to call from outside to prevent from creating multiple instances,
     * to use the singleton, you have to obtain the instance from Singleton::getInstance() instead.
     *
     * @param int $templateType
     */
    private function __construct($templateType = TemplateCreator::TEMPLATE_KV2)
    {
        $this->_secretDto = new SecretDTO();
        $this->_template = TemplateCreator::createTemplate($templateType);
        $this->_vaultSecretParams = new VaultSecretParams();
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
     * Loads json secret file.
     *
     * @param string $secretFileName
     * @param VaultSecretParams|null $vaultSecretParams
     * @return bool
     */
    public function load($secretFileName, VaultSecretParams $vaultSecretParams = null)
    {
        // Set vault secret params
        if ($vaultSecretParams instanceof VaultSecretParams) {
            $this->_vaultSecretParams = $vaultSecretParams;
        }

        // Apply vault secret params
        $this->_applyOptions();

        if ($secrets = $this->_template->parseJson($secretFileName)) {
            $this->_secretDto->secrets = array_merge($this->_secretDto->secrets, $secrets);

            return true;
        }

        return false;
    }

//    /**
//     * Returns secret by key.
//     *
//     * @param $key
//     * @param string $default
//     * @return string
//     */
//    public function getSecret($key, $default = '')
//    {
//        return isset($this->_secretDto->secrets[$key]) ? $this->_secretDto->secrets[$key] : $default;
//    }

    /**
     * Returns secret by key.
     *
     * @param string $url
     * @param string $key
     * @param int $type
     * @return string
     */
    public function getSecret($url, $key, $type = TemplateCreator::TEMPLATE_KV2)
    {
        $cache = SecretCache::getInstance();

        try {
            return $cache->get($url);
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }

    /**
     * Returns secret DTO.
     *
     * @return SecretDTO
     */
    public function getSecretDto()
    {
        return $this->_secretDto;
    }

    /**
     * Apply vault secret params.
     */
    private function _applyOptions()
    {
        // Save template
        if ($this->_vaultSecretParams->isIsSaveTemplate()) {
            $this->_template->saveTemplateToFile(
                $this->_vaultSecretParams->getSaveTemplateFilename(),
                $this->getSecretDto()
            );
        }
    }

    /**
     * Connect to Vault service and get secrets by url.
     *
     * @param $url
     * @param $token
     * @return bool|string
     * @throws Exception
     */
    private function _connectAndGet($url, $token)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "X-Vault-Token: {$token}",
            'X-Vault-Namespace: ns1/ns2/',
            'Content-Type: application/json',
        ]);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        if (curl_error($ch) || $result === false) {
            throw new Exception($ch);
        }

        curl_close($ch);

        return $result;
    }
}
