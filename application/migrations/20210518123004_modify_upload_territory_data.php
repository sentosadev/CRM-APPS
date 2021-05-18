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
            'path_uploaded_file' => array(
                'name' => 'path_upload_file',
                'type' => 'VARCHAR',
                'constraint' => 300,
                'null' => true
            ),
        );
        $this->dbforge->modify_column('upload_territory_data', $fields);
        $this->dbforge->drop_column('upload_territory_data', 'nama_dealer');
    }



    public function down()

    {
        $fields = array(
            'id_ring' => array(
                'name' => 'ring',
                'type' => 'VARCHAR',
                'constraint' => 20
            ),
            'path_upload_file' => array(
                'name' => 'path_uploaded_file',
                'type' => 'VARCHAR',
                'null' => true,
                'constraint' => 300
            ),
        );
        $this->dbforge->modify_column('upload_territory_data', $fields);

        $fields = array(
            'nama_dealer' => array('type' => 'VARCHAR', 'constraint' => '50')
        );
        $this->dbforge->add_column('upload_territory_data', $fields);
    }
}
