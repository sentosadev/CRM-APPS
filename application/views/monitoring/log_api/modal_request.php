<div class="modal fade" id="modalRequestBody">
  <div class="modal-dialog" style='width:50%'>
    <div class="modal-content">
      <div class="modal-header bg-blue disabled color-palette">
        <button style='color:white' type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" align='center'>Request Data</h4>
      </div>
      <div class="modal-body">
        <pre id="contentRequestData"></pre>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<script>
  function showModalRequestData(id) {
    values = {
      id_api_access: id
    }
    $.ajax({
      beforeSend: function() {
        $('.btnRequest').attr('disabled', true);
      },
      enctype: 'multipart/form-data',
      url: '<?= site_url(get_controller() . '/getRequest') ?>',
      type: "POST",
      data: values,
      // processData: false,
      // contentType: false,
      cache: false,
      dataType: 'JSON',
      success: function(response) {
        if (response.status == 1) {
          data = response.data.post_data;
          if (data != null) {
            var jsonPretty = JSON.stringify(JSON.parse(data), undefined, 2);
            $('#contentRequestData').html(syntaxHighlight(jsonPretty));
          }
          $('#modalRequestBody').modal('show');
        }
        $('.btnRequest').attr('disabled', false);
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
        $('.btnRequest').attr('disabled', false);
      }
    });
  }
</script>