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
 */
class TemplateCreator
{
    /**
     * Template types.
     */
    const TEMPLATE_KV1 = 1; // Vault KV version 1 structure
    const TEMPLATE_KV2 = 2; // Vault KV version 2 structure

    /**
     * Create template.
     *
     * @param int $templateType
     * @return TemplateAbstract
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
