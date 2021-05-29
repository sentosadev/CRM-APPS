<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads2 extends CI_Migration

{

    public function up()

    {
        $fields = array(
            'tanggalRegistrasi' => ['type' => 'DATETIME', 'null' => true],
            'customerId' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'kategoriModulLeads' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tanggalVisitBooth' => ['type' => 'DATETIME', 'null' => true],
            'segmenProduk' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tanggalDownloadBrosur' => ['type' => 'DATETIME', 'null' => true],
            'seriesBrosur' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tanggalWishlist' => ['type' => 'DATETIME', 'null' => true],
            'seriesWishlist' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
        );
        $this->dbforge->add_column('leads', $fields);
    }



    public function down()

    {
        $this->dbforge->drop_column('leads', 'tanggalRegistrasi');
        $this->dbforge->drop_column('leads', 'customerId');
        $this->dbforge->drop_column('leads', 'kategoriModulLeads');
        $this->dbforge->drop_column('leads', 'tanggalVisitBooth');
        $this->dbforge->drop_column('leads', 'segmenProduk');
        $this->dbforge->drop_column('leads', 'tanggalDownloadBrosur');
        $this->dbforge->drop_column('leads', 'seriesBrosur');
        $this->dbforge->drop_column('leads', 'tanggalWishlist');
        $this->dbforge->drop_column('leads', 'seriesWishlist');
    }
}
