<div class="modal fade" id="modalErrors">
  <div class="modal-dialog" style='width:50%'>
    <div class="modal-content">
      <div class="modal-header bg-red disabled color-palette">
        <button style='color:white' type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" align='center'>Detail Kesalahan</h4>
      </div>
      <div class="modal-body">
        <table class='table table-bordered table-condensed table-striped table hover' id="datatables_err" style='width:100%'>
          <thead>
            <th style='width:10%'>Baris Ke</th>
            <th style="width:90%">Keterangan Kesalahan</th>
          </thead>
        </table>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<script>
  function showModalErrorUploads() {
    $('#modalErrors').modal('show');
  }

  function setButtonDetailKesalahanTerakhir() {
    if (set_errors.length > 0) {
      setErrorsTables()
      $('#btnDetailKesalahanUploadTerakhir').show();
    } else {
      $('#btnDetailKesalahanUploadTerakhir').hide();
    }
  }


  $(document).ready(function() {
    $('#datatables_err').DataTable({
      data: set_errors
    });
  })

  function setErrorsTables() {
    $('#datatables_err').DataTable().destroy();
    $('#datatables_err').DataTable({
      data: set_errors
    });
  }
</script>