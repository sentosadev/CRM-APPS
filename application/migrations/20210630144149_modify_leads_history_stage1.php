<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_leads_history_stage1 extends CI_Migration

{

    public function up()

    {
        $this->db->query("ALTER TABLE leads_history_stage DROP PRIMARY KEY");
        $this->db->query("ALTER TABLE `leads_history_stage` ADD INDEX(`leads_id`)");
        $this->db->query("ALTER TABLE `leads_history_stage` ADD INDEX(`stageId`)");
    }



    public function down()

    {
    }
}
