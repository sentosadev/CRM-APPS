<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_absensi extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'id_absensi' => ['type' => 'INT', 'constraint' => 10, 'auto_increment' => true],
            'id_user' => ['type' => 'INT', 'constraint' => 8],
            'tanggal' => ['type' => 'DATE'],
            'jam' => ['type' => 'TIME'],
            'longitude' => ['type' => 'DOUBLE'],
            'latitude' => ['type' => 'DOUBLE'],
            'aktif' => ['type' => 'TINYINT', 'constraint' => 1, 'null' => true],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $this->dbforge->add_key('id_absensi', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('absensi', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('absensi');
    }
}
