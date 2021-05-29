<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads3 extends CI_Migration

{

    public function up()

    {

        $fields = array(
            'tanggalPengajuan' => ['type' => 'DATETIME', 'null' => true],
            'namaPengajuan' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tanggalKontakSales' => ['type' => 'DATETIME', 'null' => true],
            'noHpPengajuan' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'emailPengajuan' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kabupatenPengajuan' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
        );
        $this->dbforge->add_column('leads', $fields);
    }



    public function down()

    {
        $this->dbforge->drop_column('leads', 'tanggalPengajuan');
        $this->dbforge->drop_column('leads', 'namaPengajuan');
        $this->dbforge->drop_column('leads', 'tanggalKontakSales');
        $this->dbforge->drop_column('leads', 'noHpPengajuan');
        $this->dbforge->drop_column('leads', 'emailPengajuan');
        $this->dbforge->drop_column('leads', 'kabupatenPengajuan');
    }
}
