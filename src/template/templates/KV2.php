<?php
/**
 * Created by Abek Narynov.
 * Date: 2019-08-15
 * @link https://github.com/KebaCorp
 * @copyright Copyright (c) 2018 KebaCorp
 */

namespace KebaCorp\VaultSecret\template\templates;

use KebaCorp\VaultSecret\SecretDTO;
use KebaCorp\VaultSecret\template\TemplateAbstract;

/**
 * Class KV2.
 *
 * @package KebaCorp\VaultSecret\template\templates
 */
class KV2 extends TemplateAbstract
{
    /**
     * Parse and return secret array data.
     *
     * @param string $secretFileName
     * @return array
     */
    public function parseJson($secretFileName)
    {
        if (file_exists($secretFileName)) {
            $file = file_get_contents($secretFileName);
            if ($data = json_decode($file, true)) {

                // Sets secrets
                if (isset($data['data']) && is_array($data['data'])) {
                    return $data['data'];
                }
            }
        }

        return array();
    }

    /**
     * Returns json template string.
     *
     * @param SecretDTO $secretDto
     * @return string
     */
    public function generateJsonTemplate(SecretDTO $secretDto)
    {
        $data = array();

        $secrets = $secretDto->secrets;
        foreach ($secrets as $key => $secret) {
            $data[$key] = '';
        }

        return json_encode(array('data' => $data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
