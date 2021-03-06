<?php
/**
 * Created by Abek Narynov.
 * Date: 2020-02-08
 * @link https://github.com/KebaCorp
 * @copyright Copyright (c) 2018 KebaCorp
 */

namespace KebaCorp\VaultSecret\template\templates;

use KebaCorp\VaultSecret\template\TemplateAbstract;

/**
 * Class Kv1.
 *
 * @package KebaCorp\VaultSecret\template\templates
 * @since 2.0.0
 */
class Kv1 extends TemplateAbstract
{
    /**
     * Return secret by key from data.
     *
     * @param string $key
     * @return mixed|null
     * @since 2.0.0
     */
    public function getSecret($key)
    {
        $data = $this->getData();
        if (isset($data['data'][$key])) {
            return $data['data'][$key];
        }

        return null;
    }

    /**
     * Returns json template string.
     *
     * @return string
     * @since 2.0.0
     */
    public function generateJsonTemplate()
    {
        $data = $this->getData();

        $result = array();
        if (isset($data['data']) && is_array($data['data'])) {
            foreach ($data['data'] as $key => $secret) {
                $result[$key] = '';
            }
        }

        return json_encode($result, 256 | 128);
    }
}
