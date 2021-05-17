<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_kabupaten_kota extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'id_kabupaten_kota' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'id_provinsi' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'kabupaten_kota' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'aktif' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $this->dbforge->add_key('id_kabupaten_kota', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ms_maintain_kabupaten_kota', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('ms_maintain_kabupaten_kota');
    }
}
