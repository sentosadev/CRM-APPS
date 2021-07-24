<div class="modal fade" id="modalHistoryInteraksi">
  <div class="modal-dialog" style='width:90%'>
    <div class="modal-content">
      <div class="modal-header bg-red disabled color-palette">
        <button style='color:white' type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" align='center'>Tabel History Interaksi</h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class='table table-bordered table-condensed' id="tabelModalHistoryInteraksi" style="width:100%">
            <thead>
              <th>#</th>
              <th>Nama</th>
              <th>No. HP</th>
              <th>No. Telp</th>
              <th>Email</th>
              <th>Customer Type</th>
              <th>Event Code Invitation</th>
              <th>CMS Source</th>
              <th>Segmen Motor</th>
              <th>Series Motor</th>
              <th>Kode Unit + Warna Motor</th>
              <th>Minat Riding Test</th>
              <th>Jadwal Riding Test</th>
              <th>Source Data</th>
              <th>Platform Data</th>
              <th>Provinsi</th>
              <th>Kabupaten</th>
              <th>Kecamatan</th>
              <th>Kelurahan</th>
              <th>Assigned Dealer</th>
              <th>Frame No. Sebelumnya</th>
              <th>Keterangan</th>
              <th>Promo Unit</th>
              <th>Source Ref. ID</th>
            </thead>
          </table>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<script>
  var set_history_interaksi = 0;

  function showModalHistoryInteraksi() {
    if (set_history_interaksi == 0) {
      $('#tabelModalHistoryInteraksi').DataTable({
        "searchable": false,
        "processing": true,
        "serverSide": true,
        // "scrollX": true,
        "language": {
          "infoFiltered": "",
          "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
        },
        "order": [],
        "lengthMenu": [
          [10, 25, 50, 75, 100],
          [10, 25, 50, 75, 100]
        ],
        "ajax": {
          url: "<?php echo site_url(get_controller() . '/fetchHistoryLeadsInteraksi'); ?>",
          type: "POST",
          dataSrc: "data",
          data: function(d) {
            d.leads_id = '<?= $row->leads_id ?>';
            return d;
          },
        },
        "columnDefs": [{
            "targets": [0],
            "orderable": false
          },
          // {
          //   "targets": [8],
          //   "className": 'text-center'
          // },
          // {
          //   "targets": [3],
          //   "className": 'text-right'
          // }
        ],
      });
    }
    set_history_interaksi++;
    $("#modalHistoryInteraksi").modal('show');
  }
</script>