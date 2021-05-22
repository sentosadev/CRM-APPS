<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_api_secret_key extends CI_Migration

{

    public function up()

    {

        $this->dbforge->add_field(array(
            'api_key' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'secret_key' => ['type' => 'VARCHAR', 'constraint' => '255', 'unique' => true],
            'sender' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true],
            'receiver' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true],
            'aktif' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true]
        ));
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->add_key('api_key', TRUE);
        $this->dbforge->create_table('ms_api_secret_key', FALSE, $attributes);
    }



    public function down()

    {

        $this->dbforge->drop_table('ms_api_secret_key');
    }
}
