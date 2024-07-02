<?php

use yii\db\Migration;

/**
 * Class m240625_042517_cr_kategori
 */
class m240625_042517_cr_kategori extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240625_042517_cr_kategori cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('{{%kategori}}', [
            'idKategori' => $this->primaryKey(),
            'NamaKategori' => $this->string()->notNull(),
        ]);
    }

    public function down()
    {
        // echo "m240625_042517_cr_kategori cannot be reverted.\n";

        // return false;
        $this->dropTable('{{%kategori}}');
    }
    
}
