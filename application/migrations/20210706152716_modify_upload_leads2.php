<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_upload_leads2 extends CI_Migration

{

    public function up()

    {
        $this->db->trans_begin();

        $fields = array(
            'leads_id' => ['type' => 'VARCHAR', 'constraint' => '30','unique'=>true],
            'uploadId' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'totalDataSource' => ['type' => 'INT', 'constraint' => 5]
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

        $this->dbforge->drop_column('upload_leads', 'leads_id');
        $this->dbforge->drop_column('upload_leads', 'uploadId');
        $this->dbforge->drop_column('upload_leads', 'totalDataSource');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

    }

}