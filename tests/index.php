<?php

namespace app\tests;

require __DIR__ . '/../vendor/autoload.php';

use KebaCorp\VaultSecret\VaultSecret;
use KebaCorp\VaultSecret\VaultSecretParams;

// Set params
$vaultSecretParams = new VaultSecretParams();
$vaultSecretParams->setToken('s.g0yUXeiEf4BKBYzg7wBTU2bV');
$vaultSecretParams->setIsSaveTemplate(true);
$vaultSecretParams->setSaveTemplateFilename(__DIR__ . '/jsonTemplates/template');
VaultSecret::setParams($vaultSecretParams);

$secretsFilename1 = __DIR__ . '/jsonExamples/secretKV2_1.json';
$secretsFilename2 = __DIR__ . '/jsonExamples/secretKV2_2.json';

echo "<pre>";

try {
    var_dump(VaultSecret::getSecretFromJsonFile('MYSQL_DB_USER', $secretsFilename1));
    echo "\n";
    var_dump(VaultSecret::getSecretFromJsonFile('MYSQL_DB_USER', $secretsFilename2));
} catch (\Exception $e) {
    echo $e->getMessage();
    echo "\n";
    echo $e->getTraceAsString();
}
echo "\n";

try {
    var_dump(VaultSecret::getSecret('mysqlPassword', 'http://vault:8200/v1/test/data/mysql'));
    var_dump(VaultSecret::getSecret('mysqlPassword', 'http://vault:8200/v1/kvtest/mysql'));
} catch (\Exception $e) {
    echo $e->getMessage();
    echo "\n";
    echo $e->getTraceAsString();
}
echo "\n";

exit;
