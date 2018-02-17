<?php

use yii\db\Migration;

class m180217_113631_device_config extends Migration
{
    const TABLE_NAME = 'device_config';

    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'device_id' => $this->integer(),
            'speed' => $this->string(11),
        ]);
    }

    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
