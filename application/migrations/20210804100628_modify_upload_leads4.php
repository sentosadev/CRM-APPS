<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_upload_leads4 extends CI_Migration

{

    public function up()

    {
        $fields = array(
            'kode_event' => ['type' => 'VARCHAR', 'constraint' => 40]
        );
        $this->dbforge->add_column('upload_leads', $fields);
    }



    public function down()

    {
        $this->dbforge->drop_column('upload_leads', 'kode_event');
    }
}
