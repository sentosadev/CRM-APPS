<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Migration_Create_staging_interaksi extends CI_Migration

{

    public function up()

    {
        $this->db->query("CREATE TABLE `staging_table_leads_interaksi` (
            `batchID` varchar(30) NOT NULL,
            `nama` varchar(100) NOT NULL,
            `noHP` varchar(15) DEFAULT NULL,
            `email` varchar(100) DEFAULT NULL,
            `customerType` varchar(2) NOT NULL,
            `eventCodeInvitation` varchar(35) DEFAULT NULL,
            `customerActionDate` varchar(25) NOT NULL,
            `kabupaten` varchar(100) NOT NULL,
            `cmsSource` varchar(2) NOT NULL,
            `segmentMotor` varchar(2) NOT NULL,
            `seriesMotor` varchar(50) NOT NULL,
            `deskripsiEvent` varchar(100) NOT NULL,
            `kodeTypeUnit` varchar(50) NOT NULL,
            `kodeWarnaUnit` varchar(50) NOT NULL,
            `minatRidingTest` varchar(1) NOT NULL,
            `jadwalRidingTest` datetime DEFAULT NULL,
            `sourceData` varchar(5) NOT NULL,
            `platformData` varchar(5) NOT NULL,
            `noTelp` varchar(15) DEFAULT NULL,
            `assignedDealer` varchar(5) NOT NULL,
            `sourceRefID` varchar(50) NOT NULL,
            `provinsi` varchar(5) NOT NULL,
            `kelurahan` varchar(30) NOT NULL,
            `kecamatan` varchar(30) NOT NULL,
            `noFramePembelianSebelumnya` varchar(17) NOT NULL,
            `keterangan` varchar(250) NOT NULL,
            `promoUnit` varchar(250) NOT NULL,
            `facebook` varchar(50) NOT NULL,
            `instagram` varchar(50) NOT NULL,
            `twitter` varchar(50) NOT NULL,
            `created_at` datetime NOT NULL,
            `updated_at` datetime DEFAULT NULL,
            `updated_by` int(8) unsigned DEFAULT NULL,
            `interaksi_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `setleads` tinyint(1) unsigned DEFAULT '0',
            `periodeAwalEvent` date DEFAULT NULL,
            `periodeAkhirEvent` date DEFAULT NULL,
            PRIMARY KEY (`interaksi_id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8
        ");
    }



    public function down()

    {
    }
}
