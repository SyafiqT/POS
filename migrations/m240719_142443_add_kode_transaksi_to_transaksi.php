<?php

use yii\db\Migration;

/**
 * Class m240719_142443_add_kode_transaksi_to_transaksi
 */
class m240719_142443_add_kode_transaksi_to_transaksi extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%transaksi}}', 'kode_transaksi', $this->string()->notNull()->unique()->after('total'));
        $this->addColumn('{{%transaksi}}', 'uang_diberikan', $this->decimal(10,2)->after('kode_transaksi'));
        $this->addColumn('{{%transaksi}}', 'uang_kembalian', $this->decimal(10,2)->after('uang_diberikan'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%transaksi}}', 'kode_transaksi');
        $this->dropColumn('{{%transaksi}}', 'uang_diberikan');
        $this->dropColumn('{{%transaksi}}', 'uang_kembalian');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240719_142443_add_kode_transaksi_to_transaksi cannot be reverted.\n";

        return false;
    }
    */
}
