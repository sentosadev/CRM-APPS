<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_upload_leads extends CI_Migration

{

    public function up()

    {
        $fields = array(
            'event_code_invitation' => array('type' => 'VARCHAR', 'constraint' => 30, 'null' => false, 'unique' => true)
        );
        $this->dbforge->add_column('upload_leads', $fields);
    }



    public function down()

    {
        $this->dbforge->drop_column('upload_leads', 'event_code_invitation');
    }
}
