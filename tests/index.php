<?php

namespace app\tests;

require __DIR__ . '/../vendor/autoload.php';

use KebaCorp\VaultSecret\VaultSecret;

$secretsFilename1 = __DIR__ . '/../secretKV2.json';
$secretsFilename2 = __DIR__ . '/../secretKV2_2.json';

echo "<pre>";

var_dump(VaultSecret::load($secretsFilename1));
echo "\n";

var_dump(VaultSecret::load($secretsFilename2));
echo "\n";

var_dump(VaultSecret::getSecretDto());
echo "\n";

var_dump(VaultSecret::getSecret('MYSQL_DB_USER'));
echo "\n";

var_dump(VaultSecret::getSecret('MYSQL_DB_USER2', 'Default'));
echo "\n";

exit;
