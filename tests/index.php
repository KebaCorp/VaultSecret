<?php

namespace app\tests;

require __DIR__ . '/../vendor/autoload.php';

use KebaCorp\VaultSecret\VaultSecret;

VaultSecret::load(__DIR__ . '/../secret.json');

exit;
