<div class="modal fade" id="modalResponseData">
  <div class="modal-dialog" style='width:50%'>
    <div class="modal-content">
      <div class="modal-header bg-green disabled color-palette">
        <button style='color:white' type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" align='center'>Response Data</h4>
      </div>
      <div class="modal-body">
        <pre id="contentResponseData"></pre>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<script>
  function showModalResponseData(id) {
    values = {
      id_api_access: id
    }
    $.ajax({
      beforeSend: function() {
        $('.btnResponse').attr('disabled', true);
      },
      enctype: 'multipart/form-data',
      url: '<?= site_url(get_controller() . '/getResponse') ?>',
      type: "POST",
      data: values,
      // processData: false,
      // contentType: false,
      cache: false,
      dataType: 'JSON',
      success: function(response) {
        if (response.status == 1) {
          data = response.data.response_data;
          if (data != null) {
            var jsonPretty = JSON.stringify(JSON.parse(data), undefined, 2);
            $('#contentResponseData').html(syntaxHighlight(jsonPretty));
          }
          $('#modalResponseData').modal('show');
        }
        $('.btnResponse').attr('disabled', false);
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: '<font color="white">Peringatan</font>',
          html: '<font color="white">Telah terjadi kesalahan !</font>',
          background: '#dd4b39',
          confirmButtonColor: '#cc3422',
          confirmButtonText: 'Tutup',
          iconColor: 'white'
        })
        $('.btnResponse').attr('disabled', false);
      }
    });
  }
</script>