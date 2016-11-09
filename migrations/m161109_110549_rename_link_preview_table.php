<?php

use app\components\Migration;

class m161109_110549_rename_link_preview_table extends Migration
{
    public function up()
    {
        $this->renameTable('{{%LinkPreview}}', '{{%link_preview}}');
    }

    public function down()
    {
        $this->renameTable('{{%link_preview}}', '{{%LinkPreview}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
