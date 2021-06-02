<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_pekerjaan extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'id_pekerjaan' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'kode_pekerjaan' => ['type' => 'VARCHAR', 'constraint' => 15, 'unique' => true],
            'pekerjaan' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'golden_time' => ['type' => 'TEXT', 'null' => true],
            'script_guide' => ['type' => 'TEXT', 'null' => true],
            'aktif' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $this->dbforge->add_key('id_pekerjaan', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ms_pekerjaan', FALSE, $attributes);
    }



    public function down()

    {

        $this->dbforge->drop_table('ms_pekerjaan');
    }
}
