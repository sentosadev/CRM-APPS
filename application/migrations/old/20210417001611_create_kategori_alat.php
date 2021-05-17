<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_kategori_alat extends CI_Migration

{

    public function up()

    {

        $this->dbforge->add_field(array(
            'id_kategori' => ['type' => 'INT', 'constraint' => 4, 'auto_increment' => true],
            'kategori' => ['type' => 'VARCHAR', 'constraint' => 40],
            'deskripsi' => ['type' => 'VARCHAR', 'constraint' => 300],
            'img_big' => ['type' => 'VARCHAR', 'constraint' => 300],
            'img_small' => ['type' => 'VARCHAR', 'constraint' => 300],
            'aktif' => ['type' => 'TINYINT', 'constraint' => 1, 'null' => false, 'default' => 0],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $this->dbforge->add_key('id_kategori', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('barang_kategori', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('barang_kategori');
    }
}
