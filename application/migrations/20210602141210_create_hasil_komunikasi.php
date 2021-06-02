<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_hasil_komunikasi extends CI_Migration

{

    public function up()

    {

        $this->dbforge->add_field(array(
            'id_hasil_komunikasi' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true],
            'hasil_komunikasi' => ['type' => 'VARCHAR', 'constraint' => '30'],
            'aktif' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $this->dbforge->add_key('id_hasil_komunikasi', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ms_hasil_komunikasi', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('ms_hasil_komunikasi');
    }
}
