<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_leads_history_assigned_dealer extends CI_Migration

{

    public function up()

    {

        $this->dbforge->add_field(array(
            'id_int' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'leads_id' => ['type' => 'VARCHAR', 'constraint' => 30],
            'assignedKe' => ['type' => 'TINYINT', 'constraint' => 2],
            'assignedDealer' => ['type' => 'VARCHAR', 'constraint' => 5],
            'alasanReAssignDealer' => ['type' => 'VARCHAR', 'constraint' => 250],
            'tanggalAssignDealer' => ['type' => 'DATETIME'],
            'assignedDealerBy' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true],
            'created_at' => ['type' => 'DATETIME'],
            'created_by' => ['type' => 'INT', 'constraint' => 8, 'unsigned' => TRUE, 'null' => true]
        ));
        $attributes = array('ENGINE' => 'InnoDB');
        $this->dbforge->add_key('id_int', TRUE);
        $this->dbforge->create_table('leads_history_assigned_dealer', FALSE, $attributes);
    }



    public function down()

    {
        $this->dbforge->drop_table('leads_history_assigned_dealer');
    }
}
