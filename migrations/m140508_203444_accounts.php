<?php

use yii\db\Schema;

class m140508_203444_accounts extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        /**
        * CREATE user table
        */
        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_BIGPK,
            'username' => Schema::TYPE_STRING . ' NOT NULL',
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING,
            'role' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'created_at' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
        ], $tableOptions);

        /**
        * INSERT initial account: "user:password"
        */
        $this->insert('{{%user}}', [
            'username' => 'user',
            'email' => 'user@example.com',
            'password_hash' => '$2a$13$6Z/QJ5NCPSkvGK45ZCLnaeKk7dWh7zjihiEguQdh8fE.EEPrqEcXS',
            'role' => '10',
            'status' => '10',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        /**
        * DROP user table
        */
        $this->dropTable('{{%user}}');
    }
}
