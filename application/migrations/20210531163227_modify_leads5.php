<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads5 extends CI_Migration

{

    public function up()

    {
        $this->dbforge->drop_column('leads', 'idKecamatanDomisili');
        $this->dbforge->drop_column('leads', 'kodeKelurahanDomisili');

        

    }



    public function down()

    {
        $fields = array(
            'idKecamatanDomisili' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'kodeKelurahanDomisili' => ['type' => 'BIGINT', 'constraint' => 15, 'unsigned' => true]
        );
        $this->dbforge->add_column('leads', $fields);
        

    }

}