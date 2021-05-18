<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_dealer extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'id_dealer' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'kode_dealer' => ['type' => 'VARCHAR', 'constraint' => '10', 'unique' => true],
            'nama_dealer' => ['type' => 'VARCHAR', 'constraint' => '80'],
            'aktif' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $this->dbforge->add_key('id_dealer', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ms_dealer', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('ms_dealer');
    }
}
