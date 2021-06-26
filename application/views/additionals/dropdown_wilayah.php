<?php
if (in_array('selectProvinsi', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#id_provinsi").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/wilayah/selectProvinsi') ?>",
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
if (in_array('selectKabupatenKota', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#id_kabupaten_kota").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/wilayah/selectKabupatenKota') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              id_provinsi: $('#id_provinsi').val(),
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
if (in_array('selectKecamatan', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#id_kecamatan").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/wilayah/selectKecamatan') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              id_kabupaten_kota: $('#id_kabupaten_kota').val(),
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
if (in_array('selectKecamatan2', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#id_kecamatan2").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/wilayah/selectKecamatan') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              id_kabupaten_kota: $('#id_kabupaten_kota').val(),
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
if (in_array('selectKelurahan', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#id_kelurahan").select2({
        minimumInputLength: 4,
        ajax: {
          url: "<?= site_url('api/private/wilayah/selectKelurahan') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              id_kabupaten_kota: $('#id_kabupaten_kota').val(),
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
if (in_array('selectKelurahanFromOtherDb', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#id_kelurahan_from_other_db").select2({
        // minimumInputLength: 4,
        ajax: {
          url: "<?= site_url('api/private/wilayah/selectKelurahanFromOtherDb') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              id_kecamatan: $('#id_kecamatan_from_other_db').val(),
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
if (in_array('selectProvinsiFromOtherDb', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#id_provinsi_from_other_db").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/wilayah/selectProvinsiFromOtherDb') ?>",
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
    $(document.body).on("change", "#id_provinsi_from_other_db", function() {
      $('#id_kabupaten_kota_from_other_db').val(null).trigger('change');
      $('#id_kecamatan_from_other_db').val(null).trigger('change');
      $('#id_kelurahan_from_other_db').val(null).trigger('change');
    });
  </script>
<?php } ?>

<?php
if (in_array('selectKabupatenKotaFromOtherDb', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#id_kabupaten_kota_from_other_db").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/wilayah/selectKabupatenKotaFromOtherDb') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              id_provinsi: $('#id_provinsi_from_other_db').val(),
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
    $(document.body).on("change", "#id_provinsi_from_other_db", function() {
      $('#id_kecamatan_from_other_db').val(null).trigger('change');
      $('#id_kelurahan_from_other_db').val(null).trigger('change');
    });
  </script>
<?php } ?>

<?php
if (in_array('selectKecamatanFromOtherDb', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#id_kecamatan_from_other_db").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/wilayah/selectKecamatanFromOtherDb') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              id_kabupaten: $('#id_kabupaten_kota_from_other_db').val(),
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
    $(document.body).on("change", "#id_kecamatan_from_other_db", function() {
      $('#id_kelurahan_from_other_db').val(null).trigger('change');
    });
  </script>
<?php } ?>

<?php
if (in_array('selectKabupatenKotaFromOtherDb2', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#id_kabupaten_kota_from_other_db_2").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/wilayah/selectKabupatenKotaFromOtherDb') ?>",
          type: "POST",
          dataType: 'json',
          delay: 100,
          data: function(params) {
            return {
              id_provinsi: $('#id_provinsi_from_other_db').val(),
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
    $(document.body).on("change", "#id_provinsi_from_other_db", function() {
      $('#id_kecamatan_from_other_db').val(null).trigger('change');
      $('#id_kelurahan_from_other_db').val(null).trigger('change');
    });
  </script>
<?php } ?>