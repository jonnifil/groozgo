<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m180125_185402_create_client_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('client', [
            'id' => $this->primaryKey(5),
            'first_name' => $this->string(50)->notNull(),
            'last_name' => $this->string(50)->notNull(),
            'phone' => $this->string(15)->defaultValue(null),
            'born_date' => $this->date()->defaultValue(null),
            'sex' => $this->smallInteger(1)->notNull()->defaultValue(1)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('client');
    }
}
