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
            <th>Tgl. Penjualan</th>
            <th>Kode Dealer</th>
            <th>Nama Dealer</th>
            <th>No. Mesin</th>
            <th>SMS</th>
            <th>Call No. Mesin</th>
            <th>Call No. Rangka</th>
            <th>KPB 3 Return</th>
            <th>No. Rangka</th>
            <th>Kode Tipe</th>
            <th>Kode Warna</th>
            <th>Deskripsi Motor Customer</th>
            <th>Deskripsi Warna</th>
            <th>Harga OTR</th>
            <th>Tenor</th>
            <th>DP Gross</th>
            <th>DP Setor</th>
            <th>Jenis Customer</th>
            <th>Jenis Kelamin</th>
            <th>Tgl. Lahir</th>
            <th>Nama Customer</th>
            <th>No. KTP</th>
            <th>No. KK</th>
            <th>Alamat</th>
            <th>Nama Kelurahan</th>
            <th>Nama Kecamatan</th>
            <th>Nama Kota</th>
            <th>Kode Pos</th>
            <th>Agama</th>
            <th>Pengeluaran</th>
            <th>Pekerjaan</th>
            <th>Pekerjaan Saat Ini</th>
            <th>Pendidikan</th>
            <th>Penanggung Jawab</th>
            <th>No. HP</th>
            <th>No. Telp</th>
            <th>Aktifitas Penjualan</th>
            <th>Bersedia Dihubungi</th>
            <th>Merk Motor Sekarang</th>
            <th>Digunakan Untuk</th>
            <th>Yang Menggunakan Motor</th>
            <th>Hobi</th>
            <th>ID FLP</th>
            <th>Nama FLP Dealer</th>
            <th>Jabatan</th>
            <th>Nama FINCOY</th>
            <th>Keterangan</th>
            <th>Email</th>
            <th>Nama Tempat Kantor / Usaha</th>
            <th>Alamat Kantor</th>
            <th>Kelurahan Kantor</th>
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
          d.id_source_leads_multi = $('#id_source_leads').val()
          d.kode_dealer_sebelumnya_multi = $('#kodeDealerSebelumnya').val()
          d.assigned_dealer_multi = $('#searchAssignedDealer').val()
          d.kode_warna_multi = $('#id_warna').val()
          d.kode_type_motor_multi = $('#id_tipe').val()
          d.leads_id_multi = $('#leads_id').val()
          d.deskripsi_event_multi = $('#deskripsiEvent').val()
          d.id_status_fu_multi = $('#id_status_fu').val()
          d.jumlah_fu = $('#jumlah_fu').val()
          d.start_next_fu = $('#start_next_fu').val()
          d.end_next_fu = $('#end_next_fu').val()
          return d;
        },
      },
      "createdRow": function(row, data, index) {
        if (data[16] == 'Overdue') {
          $('td', row).eq(16).addClass('bg-red'); // 6 is index of column
        } else if (data[16] == 'On Track') {
          $('td', row).eq(16).addClass('bg-green'); // 6 is index of column
        }
        if (data[17] == 'Overdue') {
          $('td', row).eq(17).addClass('bg-red'); // 6 is index of column
        } else if (data[17] == 'On Track') {
          $('td', row).eq(17).addClass('bg-green'); // 6 is index of column
        }
      },
      "columnDefs": [{
          "targets": [0, 18],
          "orderable": false
        },
        {
          "targets": [16, 17, 18],
          "className": 'text-center'
        },
        // {
        //   "targets": [3],
        //   "className": 'text-right'
        // },
        // // { "targets":[0],"checkboxes":{'selectRow':true}}
        // { "targets":[4],"className":'text-right'}, 
        // // { "targets":[2,4,5], "searchable": false } 
      ],
    });
  });

  function search() {
    $('.serverside-tables').DataTable().ajax.reload();
  }
</script>