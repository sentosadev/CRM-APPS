<?php
if (in_array('selectTipe', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#id_tipe").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/series_tipe/selectTipe') ?>",
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
if (in_array('selectWarna', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#id_warna").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/series_tipe/selectWarna') ?>",
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
if (in_array('selectSeries', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#id_series").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/series_tipe/selectSeries') ?>",
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
if (in_array('selectTipeFromOtherDb', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#id_tipe_from_other_db").select2({
        minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/series_tipe/selectTipeFromOtherDb') ?>",
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
if (in_array('selectWarnaFromOtherDb', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#id_warna_from_other_db").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/series_tipe/selectWarnaFromOtherDb') ?>",
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