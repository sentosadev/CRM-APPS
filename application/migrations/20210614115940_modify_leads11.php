<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads11 extends CI_Migration

{

    public function up()

    {
        $this->db->trans_begin();

        $fields = array(
            'pengeluaran' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'preferensiPromoDiminatiCustomer' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'kodeDealerPembelianSebelumnya' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
        );
        $this->dbforge->add_column('leads', $fields);

        $fields = array(
            'kodeLeasingSebelumnya' => [
                'name' => 'kodeLeasingPembelianSebelumnya', 'type' => 'VARCHAR', 'constraint' => 20, 'null' => true
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

        $this->dbforge->drop_column('leads', 'pengeluaran');
        $this->dbforge->drop_column('leads', 'preferensiPromoDiminatiCustomer');
        $this->dbforge->drop_column('leads', 'kodeDealerPembelianSebelumnya');

        $fields = array(
            'kodeLeasingPembelianSebelumnya' => [
                'name' => 'kodeLeasingSebelumnya', 'type' => 'VARCHAR', 'constraint' => 20, 'null' => true
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
