<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads_stage extends CI_Migration

{

    public function up()

    {

        $fields = array(
            'no_spk' => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'followUpID' => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
        );
        $this->dbforge->add_column('leads_history_stage', $fields);

    }



    public function down()

    {
        $this->dbforge->drop_column('leads_history_stage', 'no_spk');
        $this->dbforge->drop_column('leads_history_stage', 'followUpID');
    }

}