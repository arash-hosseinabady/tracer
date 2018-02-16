<?php

use yii\db\Migration;

class m180121_083340_user_device extends Migration
{
    const TABLE_NAME = 'user_device';

    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'device_id' => $this->integer(),
            'created_at' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
