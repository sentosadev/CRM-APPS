<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads_follow_up5 extends CI_Migration

{

    public function up()

    {

        $fields = array(
            'id_status_fu' => [
                'name' => 'id_status_fu', 'type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'null' => true
            ],
            'id_media_kontak_fu' => [
                'name' => 'id_media_kontak_fu', 'type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'null' => true
            ],
        );
        $this->dbforge->modify_column('leads_follow_up', $fields);
    }



    public function down()

    {
    }
}
