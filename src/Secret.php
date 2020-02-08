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
    private function __wakeup()
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
    public function getSecret($key, $url = null, $default = null, $type = TemplateCreator::TEMPLATE_KV2)
    {
        return $this->_getOrLoadSecret($key, $url, $default, $type, false);
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
    public function getSecretFromJsonFile($key, $file = null, $default = null, $type = TemplateCreator::TEMPLATE_KV2)
    {
        return $this->_getOrLoadSecret($key, $file, $default, $type, true);
    }

    /**
     * Returns secret by key from Vault service.
     *
     * @param string $key
     * Secret key.
     *
     * @param string|null $source
     * Default Vault url to secrets.
     * If the url is null, then the url will be taken from the VaultSecretParams.
     *
     * @param mixed|null $default
     * Returns the default value if the secret was not found.
     *
     * @param int $type
     * Vault data parser template type.
     *
     * @param bool $isFromFile
     * If true, then load data from file, else load data from Vault service.
     *
     * @return mixed|null
     * Returns null if no secrets were found.
     *
     * @throws InvalidArgumentException
     *
     * @throws Exception
     *
     * @since 2.0.0
     */
    private function _getOrLoadSecret($key, $source, $default, $type, $isFromFile = false)
    {
        $secret = null;
        $cache = $this->_vaultSecretParams->getCache();
        $template = TemplateCreator::createTemplate($type);

        // If the url or filename is null, then it will be taken from the VaultSecretParams
        if ($source === null) {
            $source = $isFromFile ? $this->_vaultSecretParams->getFile() : $this->_vaultSecretParams->getUrl();
        }

        if ($cache->has($source)) {
            $template->setData($cache->get($source));
            $secret = $template->getSecret($key);
        } else {
            if ($isFromFile) {
                // Load data from file
                $data = array();
                if (file_exists($source)) {
                    $data = file_get_contents($source);
                    $data = json_decode($data, true);
                }
            } else {
                // Load data from url
                $data = $this->_connectAndGetData($source);
            }

            if ($data && is_array($data) && $cache->set($source, $data)) {
                $template->setData($data);
                $template->saveTemplateToFile(
                    $this->_vaultSecretParams->getSaveTemplateFilename()
                    . '_' . basename($source, '.json') . '.json'
                );
                $secret = $template->getSecret($key);
            }
        }

        return $secret === null ? $default : $secret;
    }

    /**
     * Connect to Vault service and get array data by url.
     *
     * @param string $url
     * @return array
     * @throws Exception
     * @since 2.0.0
     */
    private function _connectAndGetData($url)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer {$this->_vaultSecretParams->getToken()}",
            'Content-Type: application/json',
        ));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        if (curl_error($ch) || $result === false) {
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);

        $data = json_decode($result, true, JSON_UNESCAPED_UNICODE);

        return is_array($data) ? $data : array();
    }
}
