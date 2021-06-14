<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_leads_history_stage extends CI_Migration

{

    public function up()

    {
        $this->dbforge->add_field(array(
            'leads_id' => ['type' => 'VARCHAR', 'constraint' => 30],
            'stageId' => ['type' => 'VARCHAR', 'constraint' => 2],
            'created_at' => ['type' => 'DATETIME'],
            'sending_to_ahm_at' => ['type' => 'DATETIME', 'null' => true],
        ));
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->add_key('leads_id', TRUE);
        $this->dbforge->add_key('stageId', TRUE);
        $this->dbforge->create_table('leads_history_stage', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('leads_history_stage');
    }
}
