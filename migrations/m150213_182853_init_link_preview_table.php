<?php

use yii\db\Schema;
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
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_TEXT,
            'description' => Schema::TYPE_TEXT,
            'url' => Schema::TYPE_STRING . ' NOT NULL',
            'canonicalUrl' => Schema::TYPE_STRING . ' NOT NULL',
            'image' => Schema::TYPE_TEXT,
            'createdAt' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updatedAt' => Schema::TYPE_INTEGER . ' NOT NULL',
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
