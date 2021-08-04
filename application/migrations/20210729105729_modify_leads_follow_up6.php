<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads_follow_up6 extends CI_Migration

{

    public function up()

    {
        $fields = array(
            'statusProspek' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true],
        );
        $this->dbforge->add_column('leads_follow_up', $fields);
    }



    public function down()

    {
        $this->dbforge->drop_column('leads_follow_up', 'statusProspek');
    }
}
