<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_address`.
 */
class m180125_185510_create_user_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user_address', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user_address');
    }
}
