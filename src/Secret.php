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
     * Vault secret params.
     *
     * @var VaultSecretParams
     */
    private $_vaultSecretParams;

    /**
     * Gets the instance via lazy initialization (created on first usage).
     *
     * @return Secret
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
     */
    private function __construct()
    {
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
     * Get VaultSecret params object.
     *
     * @return VaultSecretParams
     */
    public function getVaultSecretParams()
    {
        return $this->_vaultSecretParams;
    }

    /**
     * Set VaultSecret params object.
     *
     * @param VaultSecretParams $vaultSecretParams
     */
    public function setVaultSecretParams($vaultSecretParams)
    {
        $this->_vaultSecretParams = $vaultSecretParams;
    }

    /**
     * Returns secret by key from Vault server.
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
     * @throws Exception
     */
    public function getSecret($key, $url = null, $default = null, $type = TemplateCreator::TEMPLATE_KV2)
    {
        $secret = null;
        $cache = SecretCache::getInstance();
        $template = TemplateCreator::createTemplate($type);

        // If the url is null, then the url will be taken from the VaultSecretParams
        if ($url === null) {
            $url = $this->_vaultSecretParams->getUrl();
        }

        if ($cache->has($url)) {
            $secret = $template->getSecret($key, $cache->get($url));
        } else {
            $data = $this->_connectAndGetData($url);
            if ($cache->set($url, $data)) {

                // Generate and save template json to files
                $this->_saveTemplate($cache);

                $secret = $template->getSecret($key, $data);
            }
        }

        // Generate and save template json to files
        $this->_saveTemplate($cache);

        return $secret === null ? $default : $secret;
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
     * @throws Exception
     */
    public function getSecretFromJsonFile($key, $file = null, $default = null, $type = TemplateCreator::TEMPLATE_KV2)
    {
        $secret = null;
        $cache = SecretCache::getInstance();
        $template = TemplateCreator::createTemplate($type);

        // If the filename is null, then the filename will be taken from the VaultSecretParams
        if ($file === null) {
            $file = $this->_vaultSecretParams->getUrl();
        }

        if ($cache->has($file)) {
            $secret = $template->getSecret($key, $cache->get($file));
        } else {
            if (file_exists($file)) {
                $file = file_get_contents($file);
                if ($data = json_decode($file, true)) {
                    if ($cache->set($file, $data)) {

                        // Generate and save template json to files
                        $this->_saveTemplate($cache);

                        $secret = $template->getSecret($key, $data);
                    }
                }
            }
        }

        return $secret === null ? $default : $secret;
    }

    /**
     * Generate and save template json to files.
     *
     * @param SecretCache $cache
     * @return int Number of saved files
     */
    private function _saveTemplate($cache)
    {
        $fileCount = 0;

        if ($this->_vaultSecretParams->isIsSaveTemplate()) {
            foreach ($cache->getAllData() as $key => $datum) {
                $fileCount += !!file_put_contents(
                    $this->_vaultSecretParams->getSaveTemplateFilename() . '_' . ($fileCount + 1) . '.json',
                    json_encode($datum, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
                );
            }
        }

        return $fileCount;
    }

    /**
     * Connect to Vault service and get array data by url.
     *
     * @param string $url
     * @return array
     * @throws Exception
     */
    private function _connectAndGetData($url)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$this->_vaultSecretParams->getToken()}",
            'Content-Type: application/json',
        ]);

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
