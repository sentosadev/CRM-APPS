<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_kuis extends CI_Migration

{

    public function up()

    {
        $this->dbforge->drop_column('kuis', 'tanggal_mulai');
        $this->dbforge->drop_column('kuis', 'jam_mulai');
        $this->dbforge->drop_column('kuis', 'tanggal_selesai');
        $this->dbforge->drop_column('kuis', 'jam_selesai');

        $fields_add = array(
            'durasi' => ['type' => 'INT', 'constraint' => 5], //Menit
        );
        $this->dbforge->add_column('kuis', $fields_add);
    }



    public function down()

    {

        $this->dbforge->drop_column('kuis', 'durasi');
        $fields_add = array(
            'tanggal_mulai' => ['type' => 'DATE'],
            'jam_mulai' => ['type' => 'TIME'],
            'tanggal_selesai' => ['type' => 'DATE'],
            'jam_selesai' => ['type' => 'TIME'],
        );
        $this->dbforge->add_column('kuis', $fields_add);
    }
}
