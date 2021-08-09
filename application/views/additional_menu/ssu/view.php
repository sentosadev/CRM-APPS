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
        <button class="btn bg-blue btn-flat" onclick="showModalDownloadFileXlsx(this)"><i class="fa fa-download"></i> Download Excel File (.xlsx)</button>
      </div>
    </div>
    <script>
      function showModalDownloadFileXlsx(el) {
        $("#modalDownloadFileXlsx").modal('show');
      }
    </script>
    <div class="modal fade" id="modalDownloadFileXlsx">
      <div class="modal-dialog" style='width:40%'>
        <div class="modal-content">
          <div class="modal-header bg-red disabled color-palette">
            <button style='color:white' type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" align='center'>Download Excel File (.xlsx)</h4>
          </div>
          <div class="modal-body">
            <form id="form_download_xlsx" class='form-horizontal' action="<?= site_url(get_controller() . '/download_xlsx') ?>" method="POST">
              <div class="form-group">
                <label class="col-sm-4 control-label">Periode *</label>
                <div class="form-input">
                  <div class="col-sm-7">
                    <input type="text" class="form-control" id='periode' required>
                    <input type="hidden" class="form-control" id='start_periode' name='start_periode'>
                    <input type="hidden" class="form-control" id='end_periode' name='end_periode'>
                    <script>
                      $(function() {
                        $('#periode').daterangepicker({
                          // opens: 'left',
                          autoUpdateInput: false,
                          locale: {
                            format: 'DD/MM/YYYY'
                          }
                        }, function(start, end, label) {
                          $('#start_periode').val(start.format('YYYY-MM-DD'));
                          $('#end_periode').val(end.format('YYYY-MM-DD'));
                        }).on('apply.daterangepicker', function(ev, picker) {
                          $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                        }).on('cancel.daterangepicker', function(ev, picker) {
                          $(this).val('');
                          $('#start_periode').val('');
                          $('#end_periode').val('');
                        });
                      });
                    </script>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <div class="row">
              <div class="col-sm-12 col-md-12" align='center'>
                <button type="button" class="btn btn-primary btn-flat" onclick="downloadFileXlsx(this)"><i class='fa fa-download'></i> Download</button>
              </div>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
        <script>
          function downloadFileXlsx(el) {
            $('#form_download_xlsx').validate({
              highlight: function(element, errorClass, validClass) {
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                  $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass);
                } else {
                  $(element).parents('.form-input').addClass('has-error');
                }
              },
              unhighlight: function(element, errorClass, validClass) {
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                  $("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass);
                } else {
                  $(element).parents('.form-input').removeClass('has-error');
                }
              },
              errorPlacement: function(error, element) {
                var elem = $(element);
                if (elem.hasClass("select2-hidden-accessible")) {
                  element = $("#select2-" + elem.attr("id") + "-container").parent();
                  error.insertAfter(element);
                } else {
                  error.insertAfter(element);
                }
              }
            })
            if ($('#form_download_xlsx').valid()) // check if form is valid
            {
              $('#form_download_xlsx').submit();
            } else {
              Swal.fire({
                icon: 'error',
                title: '<font color="white">Peringatan</font>',
                html: '<font color="white">Silahkan lengkapi field yang wajib diisi</font>',
                background: '#dd4b39',
                confirmButtonColor: '#cc3422',
                confirmButtonText: 'Tutup',
                iconColor: 'white'
              })
            }
          }
        </script>
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <div class="table-responsive">
        <table class='table table-condensed table-bordered table-striped serverside-tables' style="width:100%">
          <thead>
            <th width='5%'>#</th>
            <th>Bulan Penjualan</th>
            <th>Tgl. Penjualan</th>
            <th>Kode Dealer</th>
            <th>Nama Dealer</th>
            <th>No. Mesin</th>
            <th>No. Rangka</th>
            <th>No. Tipe</th>
            <th>No. Warna</th>
            <th>Desc. Motor</th>
            <th>Desc. Warna</th>
            <th>Harga</th>
            <th>DP Gross</th>
            <th>DP Setor</th>
            <th>Jenis Customer</th>
            <th>Jenis Kelamin</th>
            <th>Tgl. Lahir</th>
            <th>Nama Customer</th>
            <th>No. KTP</th>
            <th>No. KK</th>
            <th>Alamat</th>
            <th>Kelurahan </th>
            <th>Kecamatan</th>
            <th>Kabupaten</th>
            <th>Provinsi</th>
            <th>Kode Pos</th>
            <th>Agama</th>
            <th>Penghasilan</th>
            <th>Pekerjaan</th>
            <th>Pendidikan</th>
            <th>Penanggung Jawab</th>
            <th>No. HP</th>
            <th>No. Telp</th>
            <th>Bersedia Dihubungi</th>
            <th>Merk Motor Sekarang</th>
            <th>Digunakan Untuk</th>
            <th>Yang Menggunakan Motor</th>
            <th>Hobi</th>
            <th>ID FLP</th>
            <th>Nama FLP Dealer</th>
            <th>Jabatan</th>
            <th>Nama Fincoy</th>
            <th>Keterangan</th>
            <th>Email</th>
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