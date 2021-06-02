<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads4 extends CI_Migration

{

    public function up()

    {

        $fields = array(
            'keteranganPreferensiDealerLain' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kategoriKonsumen' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'alasanPindahDealer' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kodeDealerSebelumnya' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'gender' => ['type' => 'VARCHAR', 'constraint' => 1, 'null' => true],
            'kodeLeasingSebelumnya' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'noKtp' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'tanggalPembelianTerakhir' => ['type' => 'DATE', 'null' => true],
            'kodePekerjaan' => ['type' => 'VARCHAR', 'constraint' => 15, 'null' => true],
            'deskripsiTipeUnitPembelianTerakhir' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'promoYangDiminatiCustomer' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kategoriPreferensiDealer' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'idPendidikan' => ['type' => 'INT', 'constraint' => 3, 'null' => true, 'unsigned' => true,],
            'namaDealerPreferensiCustomer' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'idAgama' => ['type' => 'INT', 'constraint' => 3, 'null' => true, 'unsigned' => true,],
            'idKecamatanDomisili' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'tanggalRencanaPembelian' => ['type' => 'DATE', 'null' => true],
            'kodeKelurahanDomisili' => ['type' => 'BIGINT', 'constraint' => 15, 'unsigned' => true],
            'kategoriProspect' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'idKecamatanKantor' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'namaCommunity' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
        );
        $this->dbforge->add_column('leads', $fields);
    }



    public function down()

    {
        $this->dbforge->drop_column('leads', 'tanggalPengajuan');
        $this->dbforge->drop_column('leads', 'keteranganPreferensiDealerLain');
        $this->dbforge->drop_column('leads', 'kategoriKonsumen');
        $this->dbforge->drop_column('leads', 'alasanPindahDealer');
        $this->dbforge->drop_column('leads', 'kodeDealerSebelumnya');
        $this->dbforge->drop_column('leads', 'gender');
        $this->dbforge->drop_column('leads', 'kodeLeasingSebelumnya');
        $this->dbforge->drop_column('leads', 'noKtp');
        $this->dbforge->drop_column('leads', 'tanggalPembelianTerakhir');
        $this->dbforge->drop_column('leads', 'kodePekerjaan');
        $this->dbforge->drop_column('leads', 'deskripsiTipeUnitPembelianTerakhir');
        $this->dbforge->drop_column('leads', 'promoYangDiminatiCustomer');
        $this->dbforge->drop_column('leads', 'kategoriPreferensiDealer');
        $this->dbforge->drop_column('leads', 'idPendidikan');
        $this->dbforge->drop_column('leads', 'namaDealerPreferensiCustomer');
        $this->dbforge->drop_column('leads', 'idAgama');
        $this->dbforge->drop_column('leads', 'idKecamatanDomisili');
        $this->dbforge->drop_column('leads', 'tanggalRencanaPembelian');
        $this->dbforge->drop_column('leads', 'kodeKelurahanDomisili');
        $this->dbforge->drop_column('leads', 'kategoriProspect');
        $this->dbforge->drop_column('leads', 'idKecamatanKantor');
        $this->dbforge->drop_column('leads', 'namaCommunity');
    }
}
