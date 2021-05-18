<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_ring extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'id_ring' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'ring' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'aktif' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $this->dbforge->add_key('id_ring', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ms_ring', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('ms_ring');
    }
}
