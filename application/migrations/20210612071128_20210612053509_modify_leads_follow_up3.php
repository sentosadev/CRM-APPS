<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_20210612053509_modify_leads_follow_up3 extends CI_Migration

{

    public function up()

    {

        $fields = array(
            'alasanTidakKeDealerSebelumnya' => ['type' => 'VARCHAR', 'constraint' => 2, 'null' => true],
            'kodeHasilStatusFollowUp' => ['type' => 'VARCHAR', 'constraint' => 2, 'null' => true],
            'alasanNotProspectNotDeal' => ['type' => 'VARCHAR', 'constraint' => 2, 'null' => true],
            'keteranganLainnyaNotProspectNotDeal' => ['type' => 'VARCHAR', 'constraint' => 2, 'null' => true],
        );
        $this->dbforge->add_column('leads_follow_up', $fields);
    }



    public function down()

    {

        $this->dbforge->drop_column('leads_follow_up', 'alasanTidakKeDealerSebelumnya');
        $this->dbforge->drop_column('leads_follow_up', 'kodeHasilStatusFollowUp');
        $this->dbforge->drop_column('leads_follow_up', 'alasanNotProspectNotDeal');
        $this->dbforge->drop_column('leads_follow_up', 'keteranganLainnyaNotProspectNotDeal');
    }
}
