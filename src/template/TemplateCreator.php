<?php
/**
 * Created by Abek Narynov.
 * Date: 2019-08-16
 * @link https://github.com/KebaCorp
 * @copyright Copyright (c) 2018 KebaCorp
 */

namespace KebaCorp\VaultSecret\template;

use KebaCorp\VaultSecret\template\templates\Kv1;
use KebaCorp\VaultSecret\template\templates\Kv2;

/**
 * Class TemplateCreator.
 *
 * @package KebaCorp\VaultSecret
 * @since 1.1.0
 */
class TemplateCreator
{
    /**
     * Vault KV version 1 structure.
     *
     * @since 2.0.0
     */
    const TEMPLATE_KV1 = 1;

    /**
     * Vault KV version 2 structure.
     *
     * @since 1.1.0
     */
    const TEMPLATE_KV2 = 2;

    /**
     * Create template.
     *
     * @param int $templateType
     * @return TemplateAbstract
     * @since 1.1.0
     */
    static public function createTemplate($templateType = self::TEMPLATE_KV2)
    {
        switch ($templateType) {
            case self::TEMPLATE_KV1:
                return new Kv1();

            default:
                return new Kv2();
        }
    }
}
