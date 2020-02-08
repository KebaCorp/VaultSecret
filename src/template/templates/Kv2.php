<?php
/**
 * Created by Abek Narynov.
 * Date: 2019-08-15
 * @link https://github.com/KebaCorp
 * @copyright Copyright (c) 2018 KebaCorp
 */

namespace KebaCorp\VaultSecret\template\templates;

use KebaCorp\VaultSecret\template\TemplateAbstract;

/**
 * Class Kv2.
 *
 * @package KebaCorp\VaultSecret\template\templates
 * @since 1.1.0
 */
class Kv2 extends TemplateAbstract
{
    /**
     * Return secret by key from data.
     *
     * @param string $key
     * @param array $data
     * @return mixed|null
     * @since 2.0.0
     */
    public function getSecret($key, $data)
    {
        if (isset($data['data']['data'][$key])) {
            return $data['data']['data'][$key];
        }

        return null;
    }
}
