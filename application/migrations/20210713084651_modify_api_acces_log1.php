<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_api_acces_log1 extends CI_Migration

{

    public function up()

    {

        $this->db->trans_begin();

        $fields = array(
            'id_api_access' => ['type' => 'BIGINT', 'constraint' => '20', 'unsigned' => true]
        );
        $this->dbforge->add_column('ms_api_access_log', $fields);
        $this->db->query("ALTER TABLE `ms_api_access_log` ADD PRIMARY KEY(`id_api_access`);");
        $this->db->query("ALTER TABLE `ms_api_access_log` CHANGE `id_api_access` `id_api_access` BIGINT(20) NOT NULL AUTO_INCREMENT;");

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }



    public function down()

    {
        $this->db->trans_begin();

        $this->dbforge->drop_column('ms_api_access_log', 'id_api_access');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
}
