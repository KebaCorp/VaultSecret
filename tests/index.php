<?php

namespace app\tests;

require __DIR__ . '/../vendor/autoload.php';

use KebaCorp\VaultSecret\VaultSecret;

echo "<pre>";

var_dump(VaultSecret::load(__DIR__ . '/../secret.json'));
echo "\n";

var_dump(VaultSecret::load(__DIR__ . '/../secret2.json'));
echo "\n";

var_dump(VaultSecret::getSecretDto());
echo "\n";

var_dump(VaultSecret::getSecret('MYSQL_DB_USER'));
echo "\n";

var_dump(VaultSecret::getSecret('MYSQL_DB_USER2', 'Default'));
echo "\n";

exit;
