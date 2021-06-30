<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_leads_interaksi extends CI_Migration

{

    public function up()

    {

        $this->db->trans_begin();

        $this->dbforge->add_field(array(
            'leads_id' => ['type' => 'VARCHAR', 'constraint' => 30],
            'interaksi_id' => ['type' => 'VARCHAR', 'constraint' => 30],
            'nama' => ['type' => 'VARCHAR', 'constraint' => 100],
            'noHP' => ['type' => 'VARCHAR', 'constraint' => 15, 'null' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100],
            'customerType' => ['type' => 'VARCHAR', 'constraint' => 2],
            'eventCodeInvitation' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'customerActionDate' => ['type' => 'VARCHAR', 'constraint' => 25, 'null' => true],
            'idKabupaten' => ['type' => 'INT', 'constraint' => 10, 'null' => true],
            'cmsSource' => ['type' => 'VARCHAR', 'constraint' => 2, 'null' => true],
            'segmentMotor' => ['type' => 'VARCHAR', 'constraint' => 2, 'null' => true],
            'seriesMotor' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'deskripsiEvent' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kodeTypeUnit' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'kodeWarnaUnit' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'minatRidingTest' => ['type' => 'VARCHAR', 'constraint' => 1],
            'jadwalRidingTest' => ['type' => 'DATETIME', 'null' => true],
            'sourceData' => ['type' => 'VARCHAR', 'constraint' => 5, 'null' => true],
            'platformData' => ['type' => 'VARCHAR', 'constraint' => 5, 'null' => true],
            'noTelp' => ['type' => 'VARCHAR', 'constraint' => 15, 'null' => true],
            'assignedDealer' => ['type' => 'VARCHAR', 'constraint' => 5, 'null' => true],
            'sourceRefID' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'idProvinsi' => ['type' => 'INT', 'constraint' => 5, 'null' => true],
            'idKelurahan' => ['type' => 'BIGINT', 'constraint' => 20, 'null' => true],
            'idKecamatan' => ['type' => 'INT', 'constraint' => 10, 'null' => true],
            'frameNoPembelianSebelumnya' => ['type' => 'VARCHAR', 'constraint' => 17, 'null' => true],
            'keterangan' => ['type' => 'VARCHAR', 'constraint' => 250, 'null' => true],
            'promoUnit' => ['type' => 'VARCHAR', 'constraint' => 250, 'null' => true],
            'facebook' => ['type' => 'VARCHAR', 'constraint' => 50], 'null' => true,
            'instagram' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'twitter' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'created_at' => ['type' => 'DATETIME'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ));
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->add_key('leads_id', TRUE);
        $this->dbforge->add_key('interaksi_id', TRUE);
        $this->dbforge->create_table('leads_interaksi', FALSE, $attributes);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        $this->dbforge->drop_table('leads_interaksi');
    }
}
