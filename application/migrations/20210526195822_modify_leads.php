<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads extends CI_Migration

{

    public function up()

    {
        $fields = array(
            'tanggalAssignDealer' => ['type' => 'DATETIME', 'null' => true],
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
            'kodeTypeUnitProspect' => ['type' => 'VARCHAR', 'constraint' => 3, 'null' => true],
            'kodeWarnaUnitProspect' => ['type' => 'VARCHAR', 'constraint' => 2, 'null' => true],
            'picFollowUpMD' => ['type' => 'VARCHAR', 'constraint' => 2, 'null' => true],
            'ontimeSLA1' => ['type' => 'VARCHAR', 'constraint' => 1, 'null' => true],
            'picFollowUpD' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'ontimeSLA2' => ['type' => 'VARCHAR', 'constraint' => 1, 'null' => true],
            'idSPK' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'kodeIndent' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'kodeTypeUnitDeal' => ['type' => 'VARCHAR', 'constraint' => 3, 'null' => true],
            'kodeWarnaUnitDeal' => ['type' => 'VARCHAR', 'constraint' => 2, 'null' => true],
            'deskripsiPromoDeal' => ['type' => 'VARCHAR', 'constraint' => 250, 'null' => true],
            'metodePembayaranDeal' => ['type' => 'VARCHAR', 'constraint' => 2, 'null' => true],
            'kodeLeasingDeal' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'frameNo' => ['type' => 'VARCHAR', 'constraint' => 17, 'null' => true],
        );
        $this->dbforge->add_column('leads', $fields);
    }

    public function down()

    {
        $this->dbforge->drop_column('leads', 'tanggalAssignDealer');
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
        $this->dbforge->drop_column('leads', 'kodeTypeUnitProspect');
        $this->dbforge->drop_column('leads', 'kodeWarnaUnitProspect');
        $this->dbforge->drop_column('leads', 'picFollowUpMD');
        $this->dbforge->drop_column('leads', 'ontimeSLA1');
        $this->dbforge->drop_column('leads', 'picFollowUpD');
        $this->dbforge->drop_column('leads', 'ontimeSLA2');
        $this->dbforge->drop_column('leads', 'idSPK');
        $this->dbforge->drop_column('leads', 'kodeIndent');
        $this->dbforge->drop_column('leads', 'kodeTypeUnitDeal');
        $this->dbforge->drop_column('leads', 'kodeWarnaUnitDeal');
        $this->dbforge->drop_column('leads', 'deskripsiPromoDeal');
        $this->dbforge->drop_column('leads', 'metodePembayaranDeal');
        $this->dbforge->drop_column('leads', 'kodeLeasingDeal');
        $this->dbforge->drop_column('leads', 'frameNo');
    }
}
