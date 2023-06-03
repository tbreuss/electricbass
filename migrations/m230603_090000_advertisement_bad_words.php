<?php

use yii\db\Migration;

final class m230603_090000_advertisement_bad_words extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('advertisement_bad_word', [
            'id' => $this->primaryKey(),
            'word' => $this->string(50)->notNull(),
            'deleted' => $this->tinyInteger(1)->defaultValue(0),
            'created' => $this->dateTime()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('advertisement_bad_word');
    }
}
