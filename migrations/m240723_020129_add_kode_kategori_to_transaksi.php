<?php

use yii\db\Migration;

/**
 * Class m240723_020129_add_kode_kategori_to_transaksi
 */
class m240723_020129_add_kode_kategori_to_transaksi extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%kategori}}', 'kode_kategori', $this->string()->notNull()->after('idKategori'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%kategori}}', 'kode_kategori');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240723_020129_add_kode_kategori_to_transaksi cannot be reverted.\n";

        return false;
    }
    */
}
