<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_leads_customer_follow_up extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'id_int' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'leads_id' => ['type' => 'VARCHAR', 'constraint' => 30],
            'followUpKe' => ['type' => 'TINYINT', 'constraint' => 2],
            'pic' => ['type' => 'VARCHAR', 'constraint' => 60],
            'tglFollowUp' => ['type' => 'DATE'],
            'keteranganFollowUp' => ['type' => 'VARCHAR', 'constraint' => 100],
            'tglNextFollowUp' => ['type' => 'DATE'],
            'keteranganNextFollowUp' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'id_media_kontak_fu' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true],
            'id_status_fu' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true],
            'id_kategori_status_komunikasi' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true],
            'id_hasil_komunikasi' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true],
            'id_alasan_fu_not_interest' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true],
            'keteranganAlasanLainnya' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'noHP' => ['type' => 'VARCHAR', 'constraint' => 15],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true]
        ));
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->add_key('id_int', TRUE);
        $this->dbforge->add_key('leads_id');
        $this->dbforge->add_key('id_media_kontak_fu');
        $this->dbforge->add_key('id_status_fu');
        $this->dbforge->add_key('id_kategori_status_komunikasi');
        $this->dbforge->add_key('id_hasil_komunikasi');
        $this->dbforge->add_key('id_alasan_fu_not_interest');
        $this->dbforge->create_table('leads_follow_up', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('leads_follow_up');
    }
}
