<?php
if (in_array('selectDealer', $data)) { ?>
  <script>
    $(document).ready(function() {
      $("#kode_dealer").select2({
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