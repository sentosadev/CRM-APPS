<div class="modal fade" id="modalHistoryInteraksi">
  <div class="modal-dialog" style='width:90%'>
    <div class="modal-content">
      <div class="modal-header bg-red disabled color-palette">
        <button style='color:white' type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" align='center'>Tabel History Interaksi</h4>
      </div>
      <div class="modal-body">
        <table class='table table-bordered table-condensed' id="tabelModalHistoryInteraksi" style="width:100%">
          <thead>
            <th>#</th>
            <th>Nama</th>
            <th>Kode Unit + Warna Motor</th>
            <th>Series Motor</th>
            <th>Segment Motor</th>
            <th>Tgl. Riding Test</th>
            <th>CMS Score</th>
            <th>Source Data</th>
            <th>Platform Data</th>
            <th>Customer Action Date</th>
            <th>Source Ref. ID</th>
          </thead>
        </table>
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