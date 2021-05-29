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