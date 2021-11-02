<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads_follow_up extends CI_Migration

{

    public function up()

    {

        $fields = array(
            'id_tipe_kendaraan' => ['type' => 'VARCHAR', 'constraint' => 4, 'null' => true],
            'id_warna' => ['type' => 'VARCHAR', 'constraint' => 4, 'null' => true],
        );
        $this->dbforge->add_column('leads_follow_up', $fields);

    }



    public function down()

    {
        $this->dbforge->drop_column('leads_follow_up', 'id_tipe_kendaraan');
        $this->dbforge->drop_column('leads_follow_up', 'id_warna');
    }

}