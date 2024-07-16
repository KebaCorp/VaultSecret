<?php
/**
 * Created by Abek Narynov.
 * Date: 2019-08-08
 * @link https://github.com/KebaCorp
 * @copyright Copyright (c) 2018 KebaCorp
 */

namespace KebaCorp\VaultSecret;

use KebaCorp\VaultSecret\template\TemplateAbstract;
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
     * Default generated template's filename.
     */
    const GENERATED_TEMPLATE_FILENAME = 'secretsTemplate.json';

    /**
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
    public function __wakeup()
    {
    }

    /**
     * Loads json secret file.
     *
     * @param string $secretFileName
     * @param array $options
     * @return bool
     */
    public function load($secretFileName, $options = array())
    {
        if ($secrets = $this->_template->parseJson($secretFileName)) {
            $this->_secretDto->secrets = array_merge($this->_secretDto->secrets, $secrets);

            // Apply passed options
            $this->_applyOptions($options);
        }

        return false;
    }

    /**
     * Returns secret by key.
     *
     * @param $key
     * @param string $default
     * @return string
     */
    public function getSecret($key, $default = '')
    {
        return isset($this->_secretDto->secrets[$key]) ? $this->_secretDto->secrets[$key] : $default;
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
     * Apply passed options.
     *
     * @param $options
     */
    private function _applyOptions($options)
    {
        // Save template
        $isSaveTemplate = isset($options['saveTemplate']) ? $options['saveTemplate'] : true;
        if ($isSaveTemplate) {
            $filename = isset($options['saveTemplateFilename'])
                ? $options['saveTemplateFilename']
                : self::GENERATED_TEMPLATE_FILENAME;
            $this->_template->saveTemplateToFile($filename, $this->getSecretDto());
        }
    }
}
