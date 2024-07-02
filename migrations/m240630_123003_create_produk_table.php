<?php

use yii\db\Migration;

/**
 * Handles the creation of table `produk`.
 */
class m240630_123003_create_produk_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%produk}}', [
            'idProduk' => $this->primaryKey(),
            'nama' => $this->string()->notNull(),
            'harga' => $this->decimal(10, 2)->notNull(),
            'idKategori' => $this->integer()->notNull(),
            'stok' => $this->integer()->notNull(), // Add stok column
        ]);

        // Add foreign key for table `produk`
        $this->addForeignKey(
            'fk-produk-idKategori',
            '{{%produk}}',
            'idKategori',
            '{{%kategori}}',
            'idKategori',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign key first
        $this->dropForeignKey('fk-produk-idKategori', '{{%produk}}');

        // Drop produk table
        $this->dropTable('{{%produk}}');
    }
}
