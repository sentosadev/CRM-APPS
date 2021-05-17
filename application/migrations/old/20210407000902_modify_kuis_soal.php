<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_kuis_soal extends CI_Migration

{

    public function up()

    {
        $fields_add = array(
            'id_kuis_soal' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
        );
        $this->dbforge->add_key('id_kuis_soal', TRUE);
        $this->dbforge->add_column('kuis_soal', $fields_add);
    }



    public function down()

    {
        $this->dbforge->drop_column('kuis_soal', 'id_kuis_soal');
    }
}
