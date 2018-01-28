<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_address`.
 */
class m180125_185510_create_client_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('client_address', [
            'id' => $this->primaryKey(10),
            'client_id' => $this->integer(5)->notNull(),
            'address' => $this->string(256)->notNull(),
            'name' => $this->string(256)->notNull()
        ]);

        $this->addForeignKey(
            'client_address_fk1',
            'client_address',
            'client_id',
            'client',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('client_address');
    }
}
