<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_media_kontak_fu extends CI_Migration

{

    public function up()

    {

        $fields = array(
            'media_kontak_fu' => [
                'name' => 'id_media_kontak_fu', 'type' => 'INT', 'constraint' => 5
            ],
        );
        $this->dbforge->modify_column('ms_status_fu', $fields);
    }



    public function down()

    {
        $fields = array(
            'id_media_kontak_fu' => [
                'name' => 'media_kontak_fu', 'type' => 'VARCHAR', 'constraint' => 30
            ],
        );
        $this->dbforge->modify_column('ms_status_fu', $fields);
    }
}
