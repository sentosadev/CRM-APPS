<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_soal_opsi extends CI_Migration

{

    public function up()

    {
        $fields_add = array(
            'urutan_opsi' => ['type' => 'INT', 'constraint' => 3, 'unsigned' => true],
        );
        $this->dbforge->add_column('soal_opsi', $fields_add);
    }



    public function down()

    {
        $this->dbforge->drop_column('soal_opsi', 'urutan_opsi');
    }
}
