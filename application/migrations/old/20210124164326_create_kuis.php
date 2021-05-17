<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_kuis extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'id_soal' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'jenis_soal' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'isi_soal' => ['type' => 'TINYTEXT',],
            'jawaban_uraian' => ['type' => 'TINYTEXT', 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $this->dbforge->add_key('id_soal', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('soal', FALSE, $attributes);

        $this->dbforge->add_field(array(
            'id_soal' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'tipe' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'path_small' => ['type' => 'VARCHAR', 'constraint' => '200'],
            'path_big' => ['type' => 'VARCHAR', 'constraint' => '200', 'null' => true]
        ));
        $this->dbforge->add_key('id_soal', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('soal_file', FALSE, $attributes);

        $this->dbforge->add_field(array(
            'id_opsi' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'id_soal' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'opsi' => ['type' => 'VARCHAR', 'constraint' => '4'],
            'deskripsi_opsi' => ['type' => 'TINYTEXT'],
            'is_jawaban' => ['type' => 'TINYINT', 'constraint' => 1],
        ));
        $this->dbforge->add_key('id_opsi', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('soal_opsi', FALSE, $attributes);


        $this->dbforge->add_field(array(
            'id_kuis' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'nama_kuis' => ['type' => 'VARCHAR', 'constraint' => '80'],
            'deskripsi_kuis' => ['type' => 'VARCHAR', 'constraint' => '80'],
            'jenis_soal' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'tanggal_mulai' => ['type' => 'DATE'],
            'jam_mulai' => ['type' => 'TIME'],
            'tanggal_selesai' => ['type' => 'DATE'],
            'jam_selesai' => ['type' => 'TIME'],
            'status' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $this->dbforge->add_key('id_kuis', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('kuis', FALSE, $attributes);

        $this->dbforge->add_field(array(
            'id_kuis_soal' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'auto_increment' => true],
            'id_kuis' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'id_soal' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
        ));
        $this->dbforge->add_key('id_kuis_soal', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('kuis_soal', FALSE, $attributes);

        $this->dbforge->add_field(array(
            'id_pengerjaan' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'id_user' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'id_kuis' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'tanggal_mulai' => ['type' => 'DATE'],
            'jam_mulai' => ['type' => 'TIME'],
            'tanggal_selesai' => ['type' => 'DATE'],
            'jam_selesai' => ['type' => 'TIME'],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
        ));
        $this->dbforge->add_key('id_pengerjaan', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('kuis_pengerjaan', FALSE, $attributes);

        $this->dbforge->add_field(array(
            'id_pengerjaan' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'id_kuis' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'id_soal' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'jawaban_user' => ['type' => 'TINYINT',], //Uraian atau ID Opsi
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $this->dbforge->add_key('id_pengerjaan', TRUE);
        $this->dbforge->add_key('id_kuis', TRUE);
        $this->dbforge->add_key('id_soal', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('kuis_pengerjaan_jawaban', FALSE, $attributes);
    }

    public function down()

    {
        $this->dbforge->drop_table('soal');
        $this->dbforge->drop_table('soal_file');
        $this->dbforge->drop_table('soal_opsi');
        $this->dbforge->drop_table('kuis');
        $this->dbforge->drop_table('kuis_soal');
        $this->dbforge->drop_table('kuis_pengerjaan');
        $this->dbforge->drop_table('kuis_pengerjaan_jawaban');
    }
}
