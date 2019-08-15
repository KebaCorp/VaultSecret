<?php
/**
 * Created by Abek Narynov.
 * Date: 2019-08-16
 * @link https://github.com/KebaCorp
 * @copyright Copyright (c) 2018 KebaCorp
 */

namespace KebaCorp\VaultSecret\template;

use KebaCorp\VaultSecret\template\templates\KV2;

/**
 * Class TemplateCreator.
 *
 * @package KebaCorp\VaultSecret
 */
class TemplateCreator
{
    /**
     * Vault KV version 2 structure.
     */
    const TEMPLATE_KV2 = 1;

    /**
     * Create template.
     *
     * @param int $templateType
     * @return TemplateAbstract
     */
    static public function createTemplate($templateType = self::TEMPLATE_KV2)
    {
        switch ($templateType) {

            case self::TEMPLATE_KV2:
                return new KV2();

            default:
                return new KV2();
        }
    }
}
