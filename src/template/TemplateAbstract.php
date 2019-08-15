<?php
/**
 * Created by Abek Narynov.
 * Date: 2019-08-16
 * @link https://github.com/KebaCorp
 * @copyright Copyright (c) 2018 KebaCorp
 */

namespace KebaCorp\VaultSecret\template;

use KebaCorp\VaultSecret\SecretDTO;

/**
 * Class TemplateAbstract.
 *
 * @package KebaCorp\VaultSecret\template
 */
abstract class TemplateAbstract
{
    /**
     * Parse and return secret array data.
     *
     * @param string $secretFileName
     * @return array
     */
    abstract public function parseJson($secretFileName);

    /**
     * Returns json template string.
     *
     * @param SecretDTO $secretDto
     * @return string
     */
    abstract public function generateJsonTemplate(SecretDTO $secretDto);

    /**
     * Save template to file.
     *
     * @param $filename
     * @param SecretDTO $secretDto
     * @return bool
     */
    public function saveTemplateToFile($filename, SecretDTO $secretDto)
    {
        return !!file_put_contents($filename, $this->generateJsonTemplate($secretDto));
    }
}
