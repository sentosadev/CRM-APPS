<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_barang extends CI_Migration

{

    public function up()

    {

        $fields_add = array(
            'deskripsi'   => ['type' => 'VARCHAR', 'constraint' => 300, 'null' => true],
            'img_small'   => ['type' => 'VARCHAR', 'constraint' => 300, 'null' => true],
            'img_big'   => ['type' => 'VARCHAR', 'constraint' => 300, 'null' => true],
            'id_kategori' => ['type' => 'INT', 'constraint' => 4],
        );
        $this->dbforge->add_column('barang', $fields_add);
    }



    public function down()

    {

        $this->dbforge->drop_column('barang', 'deskripsi');
        $this->dbforge->drop_column('barang', 'img_small');
        $this->dbforge->drop_column('barang', 'img_big');
        $this->dbforge->drop_column('barang', 'id_kategori');
    }
}
