<?php

use yii\db\Migration;

class m180121_083323_device extends Migration
{
    const TABLE_NAME = 'device';

    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'name' => $this->string(32),
            'serial' => $this->string(32),
            'sim_number' => $this->string(32),
        ]);
    }

    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
