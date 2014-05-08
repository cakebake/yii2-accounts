yii2-accounts (dev)
============

User, manage, login and profile module for yii2 framework

### Pre-Installation
If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install the package using the following command:
~~~
php composer.phar require --prefer-dist cakebake/yii2-accounts "dev-master"
~~~

or add
~~~
"cakebake/yii2-accounts": "dev-master"
~~~
to the require section of your ```composer.json``` file and run ```php composer.phar update```.

### Installation
To access the module, you need to add this to your application configuration:

```php
<?php
    ......
    'modules' => [
        'accounts' => [
            'class' => 'cakebake\accounts\Module',
        ],
    ],
    ......
```

Check your database settings and run migration from your console:
```php yii migrate --migrationPath=@vendor/cakebake/yii2-accounts/migrations```
For more informations see [Database Migration Documentation](http://www.yiiframework.com/doc-2.0/guide-console-migrate.html#applying-migrations)

### Usage

Open your website with URI "/accounts/user/login" and sign in by:
~~~
Username: user
Password: password
~~~