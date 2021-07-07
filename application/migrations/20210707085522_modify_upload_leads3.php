<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Modify_upload_leads3 extends CI_Migration

{

    public function up()

    {

        $this->dbforge->drop_column('upload_leads', 'kodeEvent');
    }



    public function down()

    {
    }
}
