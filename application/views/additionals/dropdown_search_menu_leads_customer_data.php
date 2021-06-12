<?php
if (in_array('selectLeadsId', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#leads_id").select2({
        minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/leads_customer_data/selectLeadsId') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              searchTerm: params.term, // search term
            };
          },
          processResults: function(response) {
            return {
              results: response
            };
          },
          cache: true
        }
      });
    });
  </script>
<?php } ?>

<?php
if (in_array('selectStatusFU', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#id_status_fu").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/leads_customer_data/selectStatusFU') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              searchTerm: params.term, // search term
            };
          },
          processResults: function(response) {
            return {
              results: response
            };
          },
          cache: true
        }
      });
    });
  </script>
<?php } ?>


<?php
if (in_array('selectPlatformData', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#id_platform_data").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/leads_customer_data/selectPlatformData') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              searchTerm: params.term, // search term
            };
          },
          processResults: function(response) {
            return {
              results: response
            };
          },
          cache: true
        }
      });
    });
  </script>
<?php } ?>

<?php
if (in_array('selectSourceLeads', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#id_source_leads").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/leads_customer_data/selectSourceLeads') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              searchTerm: params.term, // search term
            };
          },
          processResults: function(response) {
            return {
              results: response
            };
          },
          cache: true
        }
      });
    });
  </script>
<?php } ?>


<?php
if (in_array('selectPekerjaan', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#kodePekerjaan").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/leads_customer_data/selectPekerjaan') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              searchTerm: params.term, // search term
            };
          },
          processResults: function(response) {
            return {
              results: response
            };
          },
          cache: true
        }
      });
    });
  </script>
<?php } ?>

<?php
if (in_array('selectSubPekerjaan', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#kodeSubPekerjaan").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/leads_customer_data/selectSubPekerjaan') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              searchTerm: params.term, // search term
              kodePekerjaan: $('#kodePekerjaan').val()
            };
          },
          processResults: function(response) {
            return {
              results: response
            };
          },
          cache: true
        }
      });
    });
  </script>
<?php } ?>

<?php
if (in_array('selectPendidikan', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#idPendidikan").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/leads_customer_data/selectPendidikan') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              searchTerm: params.term, // search term
            };
          },
          processResults: function(response) {
            return {
              results: response
            };
          },
          cache: true
        }
      });
    });
  </script>
<?php } ?>

<?php
if (in_array('selectAgama', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#idAgama").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/leads_customer_data/selectAgama') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              searchTerm: params.term, // search term
            };
          },
          processResults: function(response) {
            return {
              results: response
            };
          },
          cache: true
        }
      });
    });
  </script>
<?php } ?>


<?php
if (in_array('selectDealerSebelumnya', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#kodeDealerSebelumnya").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/leads_customer_data/selectDealer') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              searchTerm: params.term, // search term
            };
          },
          processResults: function(response) {
            return {
              results: response
            };
          },
          cache: true
        }
      });
    });
  </script>
<?php } ?>

<?php
if (in_array('selectAssignedDealer', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#searchAssignedDealer").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/leads_customer_data/selectDealer') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              searchTerm: params.term, // search term
            };
          },
          processResults: function(response) {
            return {
              results: response
            };
          },
          cache: true
        }
      });
    });
  </script>
<?php } ?>

<?php
if (in_array('selectLeasingSebelumnya', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#kodeLeasingSebelumnya").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/leads_customer_data/selectLeasing') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              searchTerm: params.term, // search term
            };
          },
          processResults: function(response) {
            return {
              results: response
            };
          },
          cache: true
        }
      });
    });
  </script>
<?php } ?>


<?php
if (in_array('selectMediaKomunikasiFolupMulti', $data)) {
  for ($i = 1; $i <= $total_fol_up; $i++) {  ?>
    <script>
      $(document).ready(function() {
        $("#id_media_kontak_fu_<?= $i ?>").select2({
          // minimumInputLength: 2,
          ajax: {
            url: "<?= site_url('api/private/leads_customer_data/selectMediaKomunikasiFolUp') ?>",
            type: "POST",
            dataType: 'json',
            delay: 100,
            data: function(params) {
              return {
                searchTerm: params.term, // search term
              };
            },
            processResults: function(response) {
              return {
                results: response
              };
            },
            cache: true
          }
        });
      });
    </script>
<?php }
} ?>

<?php
if (in_array('selectMediaKomunikasiFolupMulti', $data)) {
  for ($i = 1; $i <= $total_fol_up; $i++) {  ?>
    <script>
      $(document).ready(function() {
        $("#id_status_fu_<?= $i ?>").select2({
          // minimumInputLength: 2,
          ajax: {
            url: "<?= site_url('api/private/leads_customer_data/selectStatusFU') ?>",
            type: "POST",
            dataType: 'json',
            delay: 100,
            data: function(params) {
              return {
                searchTerm: params.term, // search term
              };
            },
            processResults: function(response) {
              return {
                results: response
              };
            },
            cache: true
          }
        });
      });
    </script>
<?php }
} ?>


<?php
if (in_array('selectKategoriStatusKomunikasiMulti', $data)) {
  for ($i = 1; $i <= $total_fol_up; $i++) {  ?>
    <script>
      $(document).ready(function() {
        $("#id_kategori_status_komunikasi_<?= $i ?>").select2({
          // minimumInputLength: 2,
          ajax: {
            url: "<?= site_url('api/private/leads_customer_data/selectKategoriStatusKomunikasi') ?>",
            type: "POST",
            dataType: 'json',
            delay: 100,
            data: function(params) {
              return {
                searchTerm: params.term, // search term
              };
            },
            processResults: function(response) {
              return {
                results: response
              };
            },
            cache: true
          }
        });
      });
    </script>
<?php }
} ?>

<?php
if (in_array('selectHasilStatusFollowUpMulti', $data)) {
  for ($i = 1; $i <= $total_fol_up; $i++) {  ?>
    <script>
      $(document).ready(function() {
        $("#kodeHasilStatusFollowUp_<?= $i ?>").select2({
          // minimumInputLength: 2,
          ajax: {
            url: "<?= site_url('api/private/leads_customer_data/selectHasilStatusFollowUp') ?>",
            type: "POST",
            dataType: 'json',
            delay: 100,
            data: function(params) {
              return {
                searchTerm: params.term, // search term
              };
            },
            processResults: function(response) {
              return {
                results: response
              };
            },
            cache: true
          }
        });
      });
    </script>
<?php }
} ?>

<?php
if (in_array('selectAlasanFuNotInterestMulti', $data)) {
  for ($i = 1; $i <= $total_fol_up; $i++) {  ?>
    <script>
      $(document).ready(function() {
        $("#id_alasan_fu_not_interest_<?= $i ?>").select2({
          // minimumInputLength: 2,
          ajax: {
            url: "<?= site_url('api/private/leads_customer_data/selectAlasanFuNotInterest') ?>",
            type: "POST",
            dataType: 'json',
            delay: 100,
            data: function(params) {
              return {
                searchTerm: params.term, // search term
              };
            },
            processResults: function(response) {
              return {
                results: response
              };
            },
            cache: true
          }
        });
      });
    </script>
<?php }
} ?>

<?php
if (in_array('selectJenisMotorYangDimilikiSekarang', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#idJenisMotorYangDimilikiSekarang").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/leads_customer_data/selectJenisMotorYangDimilikiSekarang') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              searchTerm: params.term, // search term
            };
          },
          processResults: function(response) {
            return {
              results: response
            };
          },
          cache: true
        }
      });
    });
  </script>
<?php } ?>

<?php
if (in_array('selectMerkMotorYangDimilikiSekarang', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#idMerkMotorYangDimilikiSekarang").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/leads_customer_data/selectMerkMotorYangDimilikiSekarang') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              searchTerm: params.term, // search term
            };
          },
          processResults: function(response) {
            return {
              results: response
            };
          },
          cache: true
        }
      });
    });
  </script>
<?php } ?>

<?php
if (in_array('selectSumberProspek', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#idSumberProspek").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/leads_customer_data/selectSumberProspek') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              searchTerm: params.term, // search term
            };
          },
          processResults: function(response) {
            return {
              results: response
            };
          },
          cache: true
        }
      });
    });
  </script>
<?php } ?>

<?php
if (in_array('selectSalesmanFromOtherDb', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#id_karyawan_dealer").select2({
        minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/leads_customer_data/selectSalesmanFromOtherDb') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              searchTerm: params.term, // search term
            };
          },
          processResults: function(response) {
            return {
              results: response
            };
          },
          cache: true
        }
      });
    });
  </script>
<?php } ?>

<?php
if (in_array('selectDeskripsiEvent', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#deskripsiEvent").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/leads_customer_data/selectDeskripsiEvent') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              searchTerm: params.term, // search term
            };
          },
          processResults: function(response) {
            return {
              results: response
            };
          },
          cache: true
        }
      });
    });
  </script>
<?php } ?>

<?php
if (in_array('selectJumlahFu', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#jumlah_fu").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/leads_customer_data/selectJumlahFu') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              searchTerm: params.term, // search term
            };
          },
          processResults: function(response) {
            return {
              results: response
            };
          },
          cache: true
        }
      });
    });
  </script>
<?php } ?>