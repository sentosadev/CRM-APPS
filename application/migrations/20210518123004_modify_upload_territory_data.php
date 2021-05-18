<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_upload_territory_data extends CI_Migration

{

    public function up()

    {
        $fields = array(
            'ring' => array(
                'name' => 'id_ring',
                'type' => 'TINYINT',
                'constraint' => 5,
                'unsigned' => true
            ),
        );
        $this->dbforge->modify_column('upload_territory_data', $fields);
    }



    public function down()

    {
        $fields = array(
            'id_ring' => array(
                'name' => 'ring',
                'type' => 'VARCHAR',
                'constraint' => 20
            ),
        );
        $this->dbforge->modify_column('upload_territory_data', $fields);
    }
}
