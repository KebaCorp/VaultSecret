<p align="center">
    <a href="https://github.com/KebaCorp" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/29224726?s=400&u=ed586fe0e07d9d68d1594e0020d7e8e5fd2ac3d5&v=4" height="200px">
    </a>
    <h1 align="center">VaultSecret</h1>
    <br>
</p>

The extension allows to **LOAD** the Vault secrets from Vault service or from json files and **GET** them.

For license information check the [LICENSE](LICENSE.md)-file.

[![Total Downloads](https://poser.pugx.org/kebacorp/vaultsecret/downloads)](https://packagist.org/packages/kebacorp/vaultsecret)
[![Latest Stable Version](https://poser.pugx.org/kebacorp/vaultsecret/v/stable)](https://packagist.org/packages/kebacorp/vaultsecret)
[![License](https://poser.pugx.org/kebacorp/vaultsecret/license)](https://packagist.org/packages/kebacorp/vaultsecret)



Requirements:
-------------


- PHP 5.3 and higher.



Installation:
-------------


The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist kebacorp/vaultsecret
```

or add

```
"kebacorp/vaultsecret": "*"
```

to the require section of your composer.json.



Usage
-----


**Get secret from file that contains json string:**

```php
<?php

use KebaCorp\VaultSecret\VaultSecret;

// Get secret from file that contains json string
VaultSecret::getSecret('SECRET_KEY', 'path/secret.json');
```


**Get secret from Vault service by KV2:**

```php
<?php

use KebaCorp\VaultSecret\VaultSecret;
use KebaCorp\VaultSecret\VaultSecretParams;

// Set params
$vaultSecretParams = new VaultSecretParams();
$vaultSecretParams->setToken('vaultToken');
VaultSecret::setParams($vaultSecretParams);

// Get secret from Vault service
VaultSecret::getSecret('SECRET_KEY', 'http://localhost:8200/v1/kv2/data/secretName');
```


**Get secret from Vault service by KV1:**

```php
<?php

use KebaCorp\VaultSecret\template\TemplateCreator;
use KebaCorp\VaultSecret\VaultSecret;
use KebaCorp\VaultSecret\VaultSecretParams;

// Set params
$vaultSecretParams = new VaultSecretParams();
$vaultSecretParams->setToken('vaultToken');
VaultSecret::setParams($vaultSecretParams);

// Get secret from Vault service
VaultSecret::getSecret(
    'SECRET_KEY',
    'http://localhost:8200/v1/kv2/data/secretName',
    null,
    TemplateCreator::TEMPLATE_KV1
);
```



Params
------


**Set Vault token:**

```php
<?php

use KebaCorp\VaultSecret\VaultSecret;
use KebaCorp\VaultSecret\VaultSecretParams;

$vaultSecretParams = new VaultSecretParams();
$vaultSecretParams->setToken('vaultToken');
VaultSecret::setParams($vaultSecretParams);
```


**Enable Vault secrets template json file creation:**

```php
<?php

use KebaCorp\VaultSecret\VaultSecret;
use KebaCorp\VaultSecret\VaultSecretParams;

$vaultSecretParams = new VaultSecretParams();
$vaultSecretParams->setIsSaveTemplate(true); // Not necessary
$vaultSecretParams->setSaveTemplateFilename(__DIR__ . '/jsonTemplates/template'); // Not necessary
VaultSecret::setParams($vaultSecretParams);
```


**Disable Vault secrets template json file creation:**

```php
<?php

use KebaCorp\VaultSecret\VaultSecret;
use KebaCorp\VaultSecret\VaultSecretParams;

$vaultSecretParams = new VaultSecretParams();
$vaultSecretParams->setIsSaveTemplate(false);
VaultSecret::setParams($vaultSecretParams);
```


**Set default Vault source to secrets:**

*This may be a link to the Vault service. For example: ```'http://localhost:8200/v1/kv2/data/secretName'```*
```php
<?php

use KebaCorp\VaultSecret\VaultSecret;
use KebaCorp\VaultSecret\VaultSecretParams;

$vaultSecretParams = new VaultSecretParams();
$vaultSecretParams->setSource('http://localhost:8200/v1/kv2/data/secretName');
VaultSecret::setParams($vaultSecretParams);
```

*Or it could be the path to the json file. For example: ```'path/secret.json'```*
```php
<?php

use KebaCorp\VaultSecret\VaultSecret;
use KebaCorp\VaultSecret\VaultSecretParams;

$vaultSecretParams = new VaultSecretParams();
$vaultSecretParams->setSource('path/secret.json');
VaultSecret::setParams($vaultSecretParams);
```


**Set your own cache object:**

*You can use any cache inherited from the class that implements ```CacheInterface```.*

*```SecretHybridCache``` is used by default.*

*There is also a ```SecretMemoryCache``` that stores data in RAM, but it is stored only during client connection. 
So each time they require load data from a source of secrets.*
```php
<?php

use KebaCorp\VaultSecret\SecretMemoryCache;
use KebaCorp\VaultSecret\VaultSecret;
use KebaCorp\VaultSecret\VaultSecretParams;

// Use your cache object here
$myCacheObject = SecretMemoryCache::getInstance();

$vaultSecretParams = new VaultSecretParams();
$vaultSecretParams->setCache($myCacheObject);
VaultSecret::setParams($vaultSecretParams);
```
