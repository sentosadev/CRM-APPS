<?php
if (in_array('selectAlasanPindahDealer', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#alasanPindahDealer").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/assigned_reassigned/selectAlasanReassign') ?>",
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
<?php
} ?>

<?php
if (in_array('selectAlasanReassign', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#alasanReAssignDealer").select2({
        // minimumInputLength: 2,
        ajax: {
          url: "<?= site_url('api/private/assigned_reassigned/selectAlasanReassign') ?>",
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
<?php
} ?>