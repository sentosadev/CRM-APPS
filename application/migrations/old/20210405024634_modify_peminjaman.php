<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_peminjaman extends CI_Migration

{

    public function up()

    {

        $fields_add = array(
            'catatan'   => ['type' => 'VARCHAR', 'constraint' => 300],
        );
        $this->dbforge->add_column('pinjam_barang', $fields_add);
    }



    public function down()

    {
        $this->dbforge->drop_column('pinjam_barang', 'catatan');
    }
}
