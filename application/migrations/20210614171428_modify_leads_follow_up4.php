<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads_follow_up4 extends CI_Migration

{

    public function up()

    {

        $fields = array(
            'tglFollowUp' => [
                'name' => 'tglFollowUp', 'type' => 'DATETIME', 'null' => true
            ],
        );
        $this->dbforge->modify_column('leads_follow_up', $fields);
    }



    public function down()

    {
        $fields = array(
            'tglFollowUp' => [
                'name' => 'tglFollowUp', 'type' => 'DATE', 'null' => true
            ],
        );
        $this->dbforge->modify_column('leads_follow_up', $fields);
    }
}
