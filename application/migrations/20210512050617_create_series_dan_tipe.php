<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_series_dan_tipe extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'id_tipe' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'kode_tipe' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'deskripsi_tipe' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'aktif' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $this->dbforge->add_key('id_tipe', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ms_maintain_tipe', FALSE, $attributes);

        $this->dbforge->add_field(array(
            'id_warna' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'kode_warna' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'deskripsi_warna' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'aktif' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $this->dbforge->add_key('id_warna', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ms_maintain_warna', FALSE, $attributes);

        $this->dbforge->add_field(array(
            'id_series' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'kode_series' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'deskripsi_series' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'aktif' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $this->dbforge->add_key('id_series', TRUE);
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ms_maintain_series', FALSE, $attributes);

        $this->dbforge->add_field(array(
            'id_series_tipe' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'kode_tipe' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'kode_warna' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'kode_series' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'aktif' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
        ));
        $this->dbforge->add_key('id_series_tipe', TRUE);
        $this->dbforge->add_key('kode_tipe');
        $this->dbforge->add_key('kode_warna');
        $this->dbforge->add_key('kode_series');
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('ms_maintain_series_tipe', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('ms_maintain_series_tipe');
        $this->dbforge->drop_table('ms_maintain_series');
        $this->dbforge->drop_table('ms_maintain_tipe');
        $this->dbforge->drop_table('ms_maintain_warna');
    }
}
