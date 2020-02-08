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
 */
abstract class TemplateAbstract
{
    /**
     * Return secret by key from data.
     *
     * @param string $key
     * @param array $data
     * @return mixed|null
     */
    abstract public function getSecret($key, $data);
}
