<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads10 extends CI_Migration

{

    public function up()

    {

        $this->dbforge->drop_column('leads', 'alasanTidakKeDealerSebelumnya');
        $this->dbforge->drop_column('leads', 'followUpID');
        $this->dbforge->drop_column('leads', 'tanggalFollowUp');
        $this->dbforge->drop_column('leads', 'kodeStatusKontakFU');
        $this->dbforge->drop_column('leads', 'kodeHasilStatusFollowUp');
        $this->dbforge->drop_column('leads', 'alasanNotProspectNotDeal');
        $this->dbforge->drop_column('leads', 'keteranganLainnyaNotProspectNotDeal');
        $this->dbforge->drop_column('leads', 'tanggalNextFU');
        $this->dbforge->drop_column('leads', 'statusProspect');
        $this->dbforge->drop_column('leads', 'keteranganNextFU');
    }



    public function down()

    {

        $fields = array(
            'alasanTidakKeDealerSebelumnya' => ['type' => 'VARCHAR', 'constraint' => 2, 'null' => true],
            'followUpID' => ['type' => 'VARCHAR', 'constraint' => 25, 'null' => true],
            'tanggalFollowUp' => ['type' => 'DATETIME', 'null' => true],
            'kodeStatusKontakFU' => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true],
            'kodeHasilStatusFollowUp' => ['type' => 'VARCHAR', 'constraint' => 2, 'null' => true],
            'alasanNotProspectNotDeal' => ['type' => 'VARCHAR', 'constraint' => 2, 'null' => true],
            'keteranganLainnyaNotProspectNotDeal' => ['type' => 'VARCHAR', 'constraint' => 2, 'null' => true],
            'tanggalNextFU' => ['type' => 'DATE'],
            'statusProspect' => ['type' => 'VARCHAR', 'constraint' => 2, 'null' => true],
            'keteranganNextFU' => ['type' => 'VARCHAR', 'constraint' => 250, 'null' => true],
        );
        $this->dbforge->add_column('leads', $fields);
    }
}
