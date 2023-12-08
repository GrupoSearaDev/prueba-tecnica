<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class User extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => false,
            ],
            'lastname' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => false,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => false,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
                'unique' => true
            ],
            'photo' => [
                'type'=> 'VARCHAR',
                'constraint'=> '200',
                'null' => false,
            ],
            'password' => [
                'type'=> 'VARCHAR',
                'constraint'=> '255',
                'null' => false,
            ],
            'type' => [
                'type'=> 'ENUM("Administrador", "Basico")',
                'default'=> 'Basico',
                'null' => false
            ],
            'updated_at' => [
                'type' => 'datetime',
                'null' => true
            ],
            'last_login' => [
                'type' => 'datetime',
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'datetime',
                'null' => true
            ],
            'created_at datetime default current_timestamp'
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('user');
    }

    public function down()
    {
        $this->forge->dropTable('user');
    }
}
