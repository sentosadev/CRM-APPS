<script>
  $(document).ready(function() {
    $('.select2').select2()
  })
</script>
<section class="content">
  <!-- SELECT2 EXAMPLE -->
  <div class="box box-default">
    <div class="box-header with-border">
      <div class="box-tools pull-right">
        <?= link_on_data_top(user()->id_group); ?>
      </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">

      <div class="table-responsive">
        <table class='table table-condensed table-bordered table-striped serverside-tables' style="width:100%">
          <thead>
            <th width='5%'>#</th>
            <th>Nama</th>
            <th>No. HP</th>
            <th>No. Telp</th>
            <th>Email</th>
            <th>Platform Data</th>
            <th>Source Leads</th>
            <th>Tipe & Warna Motor</th>
            <th>Deskripsi Event</th>
            <th>Total Interaksi</th>
            <th>Created At</th>
            <th>Status API2</th>
          </thead>
        </table>
      </div>
      <!-- /.row -->
    </div>
    <!-- /.box-body -->
    <div class="box-footer">

    </div>
  </div>
  <!-- /.box -->
</section>
<?php $data['data'] = ['selectLeadsId', 'selectStatusFU', 'selectPlatformData', 'selectSourceLeads', 'selectDealerSebelumnya', 'selectAssignedDealer', 'selectDeskripsiEvent', 'selectJumlahFu'];
$this->load->view('additionals/dropdown_search_menu_leads_customer_data', $data); ?>

<?php $data['data'] = ['selectWarna', 'selectTipe'];
$this->load->view('additionals/dropdown_series_tipe', $data);

$this->load->view(get_controller() . '/modal_interaksi', $data);
?>

<script>
  $(document).ready(function() {
    var dataTable = $('.serverside-tables').DataTable({
      "processing": true,
      "serverSide": true,
      "scrollX": true,
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
        url: "<?php echo site_url(get_controller() . '/fetchData'); ?>",
        type: "POST",
        dataSrc: "data",
        data: function(d) {
          d.id_platform_data_multi = $('#id_platform_data').val()
          return d;
        },
      },
      "columnDefs": [{
          "targets": [0, 1],
          "orderable": false
        },
        {
          "targets": [11],
          "className": 'text-center'
        },
        // // { "targets":[0],"checkboxes":{'selectRow':true}}
        {
          "targets": [9],
          "className": 'text-center'
        },
        // // { "targets":[2,4,5], "searchable": false } 
      ],
    });
  });

  function search() {
    $('.serverside-tables').DataTable().ajax.reload();
  }
</script>