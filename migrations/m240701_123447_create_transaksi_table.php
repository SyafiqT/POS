<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transaksi}}`.
 */
class m240701_123447_create_transaksi_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transaksi}}', [
            'idTransaksi' => $this->primaryKey(),
            'tanggal' => $this->dateTime()->notNull(),
            'total' => $this->decimal(10, 2)->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%transaksi}}');
    }
}
