<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_pengembalian_alat extends CI_Migration

{

    public function up()

    {


        $this->dbforge->add_field(array(
            'id_pengembalian' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => false],
            'id_pinjam' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'tgl_pengembalian' => ['type' => 'DATE'],
            'keterangan' => ['type' => 'VARCHAR', 'constraint' => 300],
            'status' => ['type' => 'VARCHAR', 'constraint' => 30],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
            'closed_at' => ['type' => 'DATETIME', 'null' => true],
            'closed_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $this->dbforge->add_key('id_pengembalian', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('pengembalian_alat', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('pengembalian_alat');
    }
}
