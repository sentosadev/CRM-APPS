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