<?php

use yii\db\Migration;

class m250226_200641_create_ticket_table extends Migration
{
    private string $table = "{{%request}}";
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable($this->table, [
            'request_id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'status' => "ENUM('Active', 'Resolved') NOT NULL DEFAULT 'Active'",
            'message' => $this->text()->notNull(),
            'comment' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-request-status', $this->table, 'status');
        $this->createIndex('idx-request-created_at', $this->table, 'created_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable($this->table);
    }
}
