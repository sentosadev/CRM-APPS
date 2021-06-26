<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads13 extends CI_Migration

{

    public function up()

    {
        $this->db->trans_begin();

        $fields = array(
            'idProvinsiPengajuan' => ['type' => 'INT', 'constraint' => 10, 'null' => true],
        );
        $this->dbforge->add_column('leads', $fields);

        $fields = array(
            'kabupatenPengajuan' => [
                'name' => 'idKabupatenPengajuan', 'type' => 'INT', 'constraint' => 10, 'null' => true
            ],
        );
        $this->dbforge->modify_column('leads', $fields);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        $this->db->trans_begin();

        $this->dbforge->drop_column('leads', 'idProvinsiPengajuan');

        $fields = array(
            'idKabupatenPengajuan' => [
                'name' => 'kabupatenPengajuan', 'type' => 'VARCHAR', 'constraint' => 30, 'null' => true
            ],
        );

        $this->dbforge->modify_column('leads', $fields);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
}
