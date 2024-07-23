<?php

use yii\db\Migration;

/**
 * Class m240718_112657_add_kode_gambar_to_produk
 */
class m240718_112657_add_kode_gambar_to_produk extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%produk}}', 'kode_barang', $this->string()->notNull()->unique()->after('idProduk'));
        $this->addColumn('{{%produk}}', 'gambar_barang', $this->string()->after('kode_barang'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%produk}}', 'kode_barang');
        $this->dropColumn('{{%produk}}', 'gambar_barang');
    }
}