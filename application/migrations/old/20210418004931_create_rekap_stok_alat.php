<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_rekap_stok_alat extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'id_rekap_stok' => ['type' => 'INT', 'constraint' => 10, 'auto_increment' => true, 'unsigned' => false],
            'id_barang' => ['type' => 'INT', 'constraint' => 10,],
            'sumber' => ['type' => 'VARCHAR', 'constraint' => 30],
            'id_referensi' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 30],
            'qty_baik' => ['type' => 'INT', 'constraint' => 5],
            'qty_rusak' => ['type' => 'INT', 'constraint' => 5],
            'tipe' => ['type' => 'CHAR', 'constraint' => 1],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $this->dbforge->add_key('id_rekap_stok', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('barang_rekap_stok', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('barang_rekap_stok');
    }
}
