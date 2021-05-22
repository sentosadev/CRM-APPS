<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_staging_table_leads extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'batchID' => ['type' => 'VARCHAR', 'constraint' => 30],
            'nama' => ['type' => 'VARCHAR', 'constraint' => 100],
            'noHP' => ['type' => 'VARCHAR', 'constraint' => 15],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100],
            'customerType' => ['type' => 'VARCHAR', 'constraint' => 2],
            'eventCodeInvitation' => ['type' => 'VARCHAR', 'constraint' => 20],
            'customerActionDate' => ['type' => 'VARCHAR', 'constraint' => 25],
            'kabupaten' => ['type' => 'VARCHAR', 'constraint' => 100],
            'cmsSource' => ['type' => 'VARCHAR', 'constraint' => 2],
            'segmentMotor' => ['type' => 'VARCHAR', 'constraint' => 2],
            'seriesMotor' => ['type' => 'VARCHAR', 'constraint' => 50],
            'deskripsiEvent' => ['type' => 'VARCHAR', 'constraint' => 100],
            'kodeTypeUnit' => ['type' => 'VARCHAR', 'constraint' => 50],
            'kodeWarnaUnit' => ['type' => 'VARCHAR', 'constraint' => 50],
            'minatRidingTest' => ['type' => 'VARCHAR', 'constraint' => 1],
            'jadwalRidingTest' => ['type' => 'DATETIME'],
            'sourceData' => ['type' => 'VARCHAR', 'constraint' => 5],
            'platformData' => ['type' => 'VARCHAR', 'constraint' => 5],
            'noTelp' => ['type' => 'VARCHAR', 'constraint' => 15],
            'assignedDealer' => ['type' => 'VARCHAR', 'constraint' => 5],
            'sourceRefID' => ['type' => 'VARCHAR', 'constraint' => 50],
            'provinsi' => ['type' => 'VARCHAR', 'constraint' => 5],
            'kelurahan' => ['type' => 'VARCHAR', 'constraint' => 30],
            'kecamatan' => ['type' => 'VARCHAR', 'constraint' => 30],
            'noFramePembelianSebelumnya' => ['type' => 'VARCHAR', 'constraint' => 17],
            'keterangan' => ['type' => 'VARCHAR', 'constraint' => 250],
            'promoUnit' => ['type' => 'VARCHAR', 'constraint' => 250],
            'facebook' => ['type' => 'VARCHAR', 'constraint' => 50],
            'instagram' => ['type' => 'VARCHAR', 'constraint' => 50],
            'twitter' => ['type' => 'VARCHAR', 'constraint' => 50],
            'created_at' => ['type' => 'DATETIME'],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true]
        ));
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->create_table('staging_table_leads', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('staging_table_leads');
    }
}
