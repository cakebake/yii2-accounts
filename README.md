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

### Configuration

To access the module, you need to add this to your application/console configuration (without the dots :P):

    ...
    'name' => 'My Application Name', //for emails like account activation, password reset, ...
    ...
    'params' => [
        ...
        'supportEmail' => 'support@example.com', //form emails like account activation, password reset, ...
        ...
    ]
    ...
    'components' => [
        ...
        'user' => [
            'class' => 'cakebake\accounts\components\User',
        ],
        'authManager' => [
            'class' => 'cakebake\accounts\components\AuthManager',
        ],
        ...
    ],
    ...
    'modules' => [
        ...
        'accounts' => [
            'class' => 'cakebake\accounts\Module',
        ],
        'actionlog' => [
            'class' => 'cakebake\actionlog\Module',
        ],
        ...
    ],
    ...

### Installation

Check your database settings and run migrations from your console.
For more informations see [Database Migration Documentation](http://www.yiiframework.com/doc-2.0/guide-console-migrate.html#applying-migrations)

DB Table for users:

```php yii migrate --migrationPath=@vendor/cakebake/yii2-accounts/migrations/```

DB Table for RBAC:

```php yii migrate --migrationPath=@yii/rbac/migrations/```

DB Table for ActionLog:

```php yii migrate --migrationPath=@vendor/cakebake/yii2-actionlog/migrations/```

### Usage

Open your website with URI "/accounts/user/login" and sign in by:
~~~
Username: admin
Password: password
~~~
or
~~~
Username: user
Password: password
~~~