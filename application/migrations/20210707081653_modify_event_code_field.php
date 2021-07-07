<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_event_code_field extends CI_Migration

{

    public function up()

    {
        $fields = array(
            'eventCodeInvitation' => [
                'name' => 'eventCodeInvitation', 'type' => 'VARCHAR', 'constraint' => 35
            ],
        );
        $this->dbforge->modify_column('leads', $fields);

        $fields = array(
            'kodeEvent' => [
                'name' => 'kodeEvent', 'type' => 'VARCHAR', 'constraint' => 35
            ],
        );
        $this->dbforge->modify_column('upload_leads', $fields);

        $fields = array(
            'eventCodeInvitation' => [
                'name' => 'eventCodeInvitation', 'type' => 'VARCHAR', 'constraint' => 35
            ],
        );
        $this->dbforge->modify_column('staging_table_leads', $fields);
    }



    public function down()

    {
    }
}
