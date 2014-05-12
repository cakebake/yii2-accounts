<?php

use yii\db\Schema;
use yii\db\Expression;

/**
* DATABSE SCHEMA OF THIS MODULE
*
* @version 1.0.0
* @see http://www.yiiframework.com/doc-2.0/guide-console-migrate.html
*/
class m140508_203444_accounts extends \yii\db\Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        $time = new Expression('NOW()');

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        /**
        * CREATE user table
        */
        $this->createTable('{{%account}}', [
            'id' => Schema::TYPE_BIGPK,
            'username' => Schema::TYPE_STRING . ' NOT NULL',
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING,
            'role' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
            'created_at' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
        ], $tableOptions);

        /**
        * INSERT initial accounts
        */
        //admin:password
        $this->insert('{{%account}}', [
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password_hash' => '$2a$13$6Z/QJ5NCPSkvGK45ZCLnaeKk7dWh7zjihiEguQdh8fE.EEPrqEcXS',
            'role' => '20',
            'status' => '10',
            'created_at' => $time,
            'updated_at' => $time,
        ]);
        //user:password
        $this->insert('{{%account}}', [
            'username' => 'user',
            'email' => 'user@example.com',
            'password_hash' => '$2a$13$6Z/QJ5NCPSkvGK45ZCLnaeKk7dWh7zjihiEguQdh8fE.EEPrqEcXS',
            'role' => '10',
            'status' => '10',
            'created_at' => $time,
            'updated_at' => $time,
        ]);
    }

    public function safeDown()
    {
        /**
        * DROP user table
        */
        $this->dropTable('{{%account}}');
    }
}
