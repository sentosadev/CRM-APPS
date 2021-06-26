<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_series_tipe extends CI_Migration

{

    public function up()

    {
        $this->db->trans_begin();

        $fields = array(
            'kode_series' => [
                'name' => 'kode_series', 'type' => 'VARCHAR', 'constraint' => 15
            ],
        );
        $this->dbforge->modify_column('ms_maintain_series', $fields);

        $this->dbforge->modify_column('ms_maintain_series_tipe', $fields);

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
            'kode_series' => [
                'name' => 'kode_series', 'type' => 'VARCHAR', 'constraint' => 5
            ],
        );
        $this->dbforge->modify_column('ms_maintain_series', $fields);

        $this->dbforge->modify_column('ms_maintain_series_tipe', $fields);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
}
