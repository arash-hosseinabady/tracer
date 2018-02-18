<?php

use yii\db\Migration;

class m180121_083419_location_info extends Migration
{
    const TABLE_NAME = 'location_info';

    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'time' => $this->integer(),
            'device_id' => $this->integer(),
            'latitude' => $this->string(32),
            'longitude' => $this->string(32),
            'speed' => $this->string(32),
            'course' => $this->string(32),
            'battery_voltage' => $this->string(32),
            'door' => $this->string(32),
            'shock_sensor' => $this->string(32),
            'motor' => $this->boolean(),
            'command1' => $this->string(32),
            'command2' => $this->string(32),
            'created_at' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
