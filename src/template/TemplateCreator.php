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
use KebaCorp\VaultSecret\VaultSecret;

/**
 * Class TemplateCreator.
 *
 * @package KebaCorp\VaultSecret
 * @since 1.1.0
 */
class TemplateCreator
{
    /**
     * Create template.
     *
     * @param int $templateType
     * @return TemplateAbstract
     * @since 1.1.0
     */
    static public function createTemplate($templateType = VaultSecret::TEMPLATE_TYPE_KV2)
    {
        switch ($templateType) {
            case VaultSecret::TEMPLATE_TYPE_KV1:
                return new Kv1();

            default:
                return new Kv2();
        }
    }
}
