<?php
/**
 * Created by Abek Narynov.
 * Date: 2019-08-16
 * @link https://github.com/KebaCorp
 * @copyright Copyright (c) 2018 KebaCorp
 */

namespace KebaCorp\VaultSecret\template;

/**
 * Class TemplateAbstract.
 *
 * @package KebaCorp\VaultSecret\template
 * @since 1.1.0
 */
abstract class TemplateAbstract
{
    /**
     * Secrets data.
     *
     * @var array
     * @since 2.0.0
     */
    private $_data = array();

    /**
     * Return secret by key from data.
     *
     * @param string $key
     * @return mixed|null
     * @since 2.0.0
     */
    abstract public function getSecret($key);

    /**
     * Returns json template string.
     *
     * @return string
     * @since 1.1.0
     */
    abstract public function generateJsonTemplate();

    /**
     * Generate and save template json to files.
     *
     * @param string $filename
     * @return bool
     * @since 1.1.0
     */
    public function saveTemplateToFile($filename)
    {
        return !!file_put_contents($filename, $this->generateJsonTemplate());
    }

    /**
     * Get secrets data.
     *
     * @return array
     * @since 2.0.0
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * Set secrets data.
     *
     * @param array $data
     * @return bool
     * @since 2.0.0
     */
    public function setData($data)
    {
        if (is_array($data)) {
            $this->_data = $data;

            return true;
        }

        return false;
    }
}
