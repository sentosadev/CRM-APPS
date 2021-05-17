<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_pinjam_barang extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'id_barang' => ['type' => 'INT', 'constraint' => 10, 'auto_increment' => true],
            'nama_barang' => ['type' => 'VARCHAR', 'constraint' => 50],
            'stok' => ['type' => 'INT'],
            'aktif' => ['type' => 'TINYINT', 'constraint' => 1, 'null' => true],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $this->dbforge->add_key('id_barang', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('barang', FALSE, $attributes);


        $this->dbforge->add_field(array(
            'id_pinjam' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'id_user' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => true],
            'tgl_awal' => ['type' => 'DATE'],
            'tgl_akhir' => ['type' => 'DATE'],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'approved_at' => ['type' => 'DATETIME', 'null' => true],
            'approved_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
            'pengembalian_at' => ['type' => 'DATETIME', 'null' => true],
            'pengembalian_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $this->dbforge->add_key('id_pinjam', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('pinjam_barang', FALSE, $attributes);

        $this->dbforge->add_field(array(
            'id_pinjam' => ['type' => 'INT', 'constraint' => 10],
            'id_barang' => ['type' => 'INT', 'constraint' => 10],
            'qty_pinjam' => ['type' => 'INT', 'constraint' => 5],
            'qty_kembali' => ['type' => 'INT', 'constraint' => 5],
        ));
        $this->dbforge->add_key('id_pinjam', TRUE);
        $this->dbforge->add_key('id_barang', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('pinjam_barang_detail', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('pinjam_barang_detail');
        $this->dbforge->drop_table('pinjam_barang');
        $this->dbforge->drop_table('barang');
    }
}
