<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transaksi_detail}}`.
 */
class m240701_123451_create_transaksi_detail_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transaksi_detail}}', [
            'idDetail' => $this->primaryKey(),
            'idTransaksi' => $this->integer()->notNull(),
            'idProduk' => $this->integer()->notNull(),
            'jumlah' => $this->integer()->notNull(),
            'harga' => $this->decimal(10, 2)->notNull(),
        ]);

        $this->addForeignKey(
            'fk-transaksi_detail-idTransaksi',
            '{{%transaksi_detail}}',
            'idTransaksi',
            '{{%transaksi}}',
            'idTransaksi',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-transaksi_detail-idProduk',
            '{{%transaksi_detail}}',
            'idProduk',
            '{{%produk}}',
            'idProduk',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-transaksi_detail-idTransaksi', '{{%transaksi_detail}}');
        $this->dropForeignKey('fk-transaksi_detail-idProduk', '{{%transaksi_detail}}');
        $this->dropTable('{{%transaksi_detail}}');
    }
}
