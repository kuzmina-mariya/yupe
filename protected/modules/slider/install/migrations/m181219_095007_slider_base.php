<?php

class m181219_095007_slider_base extends yupe\components\DbMigration
{
    public function safeUp()
    {
        $this->createTable(
            '{{slider_slide}}',
            [
                'id' => 'pk',
                'entity_module_name' => 'varchar(40) DEFAULT NULL',
                'entity_name' => 'varchar(40) DEFAULT NULL',
                'entity_id' => 'integer(11) DEFAULT NULL',
                'title' => 'varchar(250) DEFAULT NULL',
                'link' => 'varchar(250) DEFAULT NULL',
                'full_text' => 'text DEFAULT NULL',
                'image' => 'varchar(250) DEFAULT NULL',
                'sort' => "integer NOT NULL DEFAULT '1'",
                'status' => "integer NOT NULL DEFAULT '1'",
                'create_time' => 'datetime NOT NULL',
                'update_time' => 'datetime NOT NULL',
            ],
            $this->getOptions()
        );

        $this->createIndex("ix_{{slider_slide}}_entity_module_name", '{{slider_slide}}', "entity_module_name", false);
        $this->createIndex("ix_{{slider_slide}}_entity_name", '{{slider_slide}}', "entity_name", false);
        $this->createIndex("ix_{{slider_slide}}_entity_id", '{{slider_slide}}', "entity_id", false);
    }

    public function safeDown()
    {
        $this->dropTable("{{slider_slide}}");
    }
}