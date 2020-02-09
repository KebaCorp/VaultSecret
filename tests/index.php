<?php

namespace app\tests;

require __DIR__ . '/../vendor/autoload.php';

use KebaCorp\VaultSecret\VaultSecret;
use KebaCorp\VaultSecret\VaultSecretParams;
use Psr\SimpleCache\InvalidArgumentException;

$startTime = microtime(true);

// Set params
$vaultSecretParams = new VaultSecretParams();
$vaultSecretParams->setToken('s.g0yUXeiEf4BKBYzg7wBTU2bV');
$vaultSecretParams->setIsSaveTemplate(true);
$vaultSecretParams->setSaveTemplateFilename(__DIR__ . '/jsonTemplates/template');
VaultSecret::setParams($vaultSecretParams);

$secretsFilename1 = __DIR__ . '/jsonExamples/secretKV1_1.json';
$secretsFilename2 = __DIR__ . '/jsonExamples/secretKV2_1.json';
$secretsFilename3 = __DIR__ . '/jsonExamples/secretKV2_2.json';

echo "<pre>";

try {
    // KV1 from file test
    echo "<b>KV1 from file test:</b>\n";
    var_dump(VaultSecret::getSecret(
        'password',
        $secretsFilename1,
        6667,
        VaultSecret::TEMPLATE_TYPE_KV1
    ));

    echo "\n";

    // KV2 from file test
    echo "<b>KV2 from file test:</b>\n";
    var_dump(VaultSecret::getSecret('MYSQL_DB_USER', $secretsFilename2));

    echo "\n";

    // KV2 from file test
    echo "<b>KV2 from file test:</b>\n";
    var_dump(VaultSecret::getSecret('MYSQL_DB_USER', $secretsFilename3));

    echo "\n";

    // KV1 by url test
    echo "<b>KV1 from url test:</b>\n";
    var_dump(VaultSecret::getSecret(
        'password',
        'http://vault:8200/v1/kvtest/mysql',
        null,
        VaultSecret::TEMPLATE_TYPE_KV1
    ));

    // KV1 by url test
    echo "<b>KV1 from url test:</b>\n";
    var_dump(VaultSecret::getSecret(
        'username',
        'http://vault:8200/v1/kvtest/mysql',
        null,
        VaultSecret::TEMPLATE_TYPE_KV1
    ));

    echo "\n";

    // KV2 by url test
    echo "<b>KV2 from url test:</b>\n";
    var_dump(VaultSecret::getSecret('password', 'http://vault:8200/v1/test/data/mongodb'));
} catch (\Exception $e) {
    echo $e->getMessage();
    echo "\n";
    echo $e->getTraceAsString();
} catch (InvalidArgumentException $e) {
    echo $e->getMessage();
    echo "\n";
    echo $e->getTraceAsString();
}

echo "\n";
echo 'Execution time: ' . (microtime(true) - $startTime) . ' seconds.';

exit;
