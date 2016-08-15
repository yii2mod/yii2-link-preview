<?php

use yii\db\Migration;

/**
 * Class m150213_182853_init_link_preview_table
 */
class m150213_182853_init_link_preview_table extends Migration
{
    /**
     * migrate up
     */
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%LinkPreview}}', [
            'id' => $this->primaryKey(),
            'title' => $this->text(),
            'description' => $this->text(),
            'url' => $this->string()->notNull(),
            'canonicalUrl' => $this->string()->notNull(),
            'image' => $this->text(),
            'code' => $this->text(),
            'createdAt' => $this->integer()->notNull(),
            'updatedAt' => $this->integer()->notNull(),
        ], $tableOptions);

    }

    /**
     * migrate down
     */
    public function down()
    {
        $this->dropTable('{{%LinkPreview}}');
    }
}