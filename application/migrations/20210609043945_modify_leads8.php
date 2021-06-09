<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads8 extends CI_Migration

{

    public function up()

    {
        $this->db->trans_begin();

        $fields = array(
            'customerId' => ['type' => 'VARCHAR', 'constraint' => 30, 'unique' => true, 'null' => false],
        );
        $this->dbforge->modify_column('leads', $fields);

        $fields = array(
            'tempatLahir' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'tanggalLahir' => ['type' => 'DATE', 'null' => true],
            'deskripsiPekerjaan' => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'alamat' => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'id_karyawan_dealer' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'idProspek' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
        );
        $this->dbforge->add_column('leads', $fields);

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
            'customerId' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
        );
        $this->dbforge->modify_column('leads', $fields);

        $this->dbforge->drop_column('leads', 'tempatLahir');
        $this->dbforge->drop_column('leads', 'tanggalLahir');
        $this->dbforge->drop_column('leads', 'deskripsiPekerjaan');
        $this->dbforge->drop_column('leads', 'alamat');
        $this->dbforge->drop_column('leads', 'id_karyawan_dealer');
        $this->dbforge->drop_column('leads', 'idProspek');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
}
