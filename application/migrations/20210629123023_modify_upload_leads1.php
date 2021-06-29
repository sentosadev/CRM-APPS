<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_upload_leads1 extends CI_Migration

{

    public function up()

    {
        $this->db->trans_begin();

        $fields = array(
            'id_platform_data' => [
                'name' => 'id_platform_data', 'type' => 'VARCHAR', 'constraint' => 10
            ],
            'id_source_leads' => [
                'name' => 'id_source_leads', 'type' => 'VARCHAR', 'constraint' => 10
            ],
        );
        $this->dbforge->modify_column('upload_leads', $fields);

        $fields = array(
            'kodeDealerSebelumnya' => ['type' => 'VARCHAR', 'constraint' => '10', 'null' => true],
            'customerId' => ['type' => 'VARCHAR', 'constraint' => '30', 'null' => true],
            'alamat' => ['type' => 'VARCHAR', 'constraint' => '300', 'null' => true],
            'alamat' => ['type' => 'VARCHAR', 'constraint' => '300', 'null' => true],
            'idPropinsi' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'idKecamatan' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true, 'null' => true],
            'idKelurahan' => ['type' => 'BIGINT', 'constraint' => 15, 'unsigned' => true, 'null' => true],
            'gender' => ['type' => 'VARCHAR', 'constraint' => '1', 'null' => true],
            'idPekerjaan' => ['type' => 'VARCHAR', 'constraint' => '15', 'null' => true],
            'idPendidikan' => ['type' => 'INT', 'constraint' => '3', 'null' => true],
            'idAgama' => ['type' => 'INT', 'constraint' => '3', 'null' => true],
            'tanggalSalesSebelumnya' => ['type' => 'DATE', 'null' => true],
            'kodeLeasingSebelumnya' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'kodeEvent' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
        );
        $this->dbforge->add_column('upload_leads', $fields);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        $this->db->trans_begin();

        $fields = array(
            'id_platform_data' => [
                'name' => 'id_platform_data', 'type' => 'INT', 'constraint' => 5
            ],
            'id_source_leads' => [
                'name' => 'id_source_leads', 'type' => 'INT', 'constraint' => 5
            ],
        );
        $this->dbforge->modify_column('upload_leads', $fields);

        $this->dbforge->drop_column('upload_leads', 'kodeDealerSebelumnya');
        $this->dbforge->drop_column('upload_leads', 'customerId');
        $this->dbforge->drop_column('upload_leads', 'alamat');
        $this->dbforge->drop_column('upload_leads', 'alamat');
        $this->dbforge->drop_column('upload_leads', 'idPropinsi');
        $this->dbforge->drop_column('upload_leads', 'idKecamatan');
        $this->dbforge->drop_column('upload_leads', 'idKelurahan');
        $this->dbforge->drop_column('upload_leads', 'gender');
        $this->dbforge->drop_column('upload_leads', 'idPekerjaan');
        $this->dbforge->drop_column('upload_leads', 'idPendidikan');
        $this->dbforge->drop_column('upload_leads', 'idAgama');
        $this->dbforge->drop_column('upload_leads', 'tanggalSalesSebelumnya');
        $this->dbforge->drop_column('upload_leads', 'kodeLeasingSebelumnya');
        $this->dbforge->drop_column('upload_leads', 'kodeEvent');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
}
