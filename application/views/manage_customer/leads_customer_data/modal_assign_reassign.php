<div class="modal fade" id="modalAssign">
  <div class="modal-dialog" style='width:90%'>
    <div class="modal-content">
      <div class="modal-header bg-red disabled color-palette">
        <button style='color:white' type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" align='center'>Assign Dealer</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-default box-solid">
              <div class="box-header with-border">
                <h3 class="box-title">Leads Data</h3>
                <div class="box-tools pull-right">
                  <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                  </button> -->
                </div>
                <!-- /.box-tools -->
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <form id="form_registrasi" class='form-horizontal form_'>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Leads ID</label>
                    <div class="form-input">
                      <div class="col-sm-4">
                        <input type="text" class="form-control" disabled id="modal_assg_leads_id">
                      </div>
                    </div>
                    <label class="col-sm-2 control-label">Kode Dealer Pembelian Sebelumnya</label>
                    <div class="form-input">
                      <div class="col-sm-4">
                        <input type="text" class="form-control" id="modal_assg_kodeDealerPembelianSebelumnya" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Nama</label>
                    <div class="form-input">
                      <div class="col-sm-4">
                        <input type="text" class="form-control" disabled id="modal_assg_nama">
                      </div>
                    </div>
                    <label class="col-sm-2 control-label">Nama Dealer Pembelian Sebelumnya</label>
                    <div class="form-input">
                      <div class="col-sm-4">
                        <input type="text" class="form-control" id="modal_assg_namaDealerPembelianSebelumnya" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Kecamatan</label>
                    <div class="form-input">
                      <div class="col-sm-4">
                        <input type="text" class="form-control" disabled id="modal_assg_deskripsiKecamatanDomisili">
                      </div>
                    </div>
                    <label class="col-sm-2 control-label">Kabupaten</label>
                    <div class="form-input">
                      <div class="col-sm-4">
                        <input type="text" class="form-control" id="modal_assg_deskripsiKabupatenKotaDomisili" disabled>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="box-footer" align='center'>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="box box-default box-solid">
              <div class="box-header with-border">
                <h3 class="box-title">Main Dealer/Dealer Leads Distribution Checkbox*</h3>
                <div class="box-tools pull-right">
                  <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                  </button> -->
                </div>
                <!-- /.box-tools -->
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <div class="form-horizontal">
                  <div class="form-group">
                    <div class="form-input">
                      <div class="col-sm-2">
                        <input type="checkbox" class="flat-red" name="territory_data" id='territory_data' checked> Territory Data
                      </div>
                      <div class="col-sm-2">
                        <input type="checkbox" class="flat-red" name="dealer_mapping" id='dealer_mapping' checked> Dealer Mapping
                      </div>
                      <div class="col-sm-2">
                        <input type="checkbox" class="flat-red" name="nos_score" id='nos_score' checked> NOS Score
                      </div>
                      <div class="col-sm-3">
                        <input type="checkbox" class="flat-red" name="dealer_crm_score" id='dealer_crm_score' checked> Dealer CRM Score
                      </div>
                      <div class="col-sm-3">
                        <input type="checkbox" class="flat-red" name="workload_dealer" id='workload_dealer' checked> Workload Dealer
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-6 control-label">Threshold actual avg assigned leads per salespeople <span id="threshold_per_salespeople_label">*</span> : </label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" name="threshold_per_salespeople" id='threshold_per_salespeople' required>
                      </div>
                    </div>
                  </div>
                </div>
                <div style='font-size:10pt'>* Jika tidak menemukan kondisi ideal, boleh melakukan uncheck dari prioritas terbawah, yaitu dari kanan (Workload Dealer) ke kiri (Territory Data)</div>
              </div>
              <div class="box-footer" align='center'>
                <button class='btn btn-primary' type="button" onclick="searchAssignDealer()">Filter</button>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="box box-default box-solid">
              <div class="box-header with-border">
                <h3 class="box-title">Hasil Filter Rekomendasi Dealer</h3>
                <!-- /.box-tools -->
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <div class="table-responsive">
                  <table class='table table-condensed table-stiped table-bordered' id='tbl_assign_dealer' style="width:100%">
                    <thead>
                      <th>No.</th>
                      <th>Kode Dealer</th>
                      <th>Nama Dealer</th>
                      <th>Territory</th>
                      <th>Channel Mapping</th>
                      <th>NOS Score</th>
                      <th>CRM Score</th>
                      <th>Sales People On Duty</th>
                      <th>Workload</th>
                      <th>Workload Capacity</th>
                      <th>Action</th>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
            <!-- /.box -->
          </div>
        </div>
        <div class="row">
          <form id='form_alasan_pindah_dealer' style="display:none">
            <div class="col-sm-6">
              <div class="form-group">
                <label>Alasan Pindah Dealer *</label>
                <div class="form-input">
                  <select style='width:100%' id="alasanPindahDealer" class='form-control' name='alasanPindahDealer' required>
                    <option value=''>- Pilih -</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-sm-6" style="display:none" id="input_alasan_pindah_dealer_lainnya">
              <div class="form-group">
                <label>Alasan Pindah Dealer Lainnya *</label>
                <div class="form-input">
                  <input type="text" class="form-control" id="alasanPindahDealerLainnya" name='alasanPindahDealerLainnya' required>
                </div>
              </div>
            </div>
          </form>
          <script>
            $("#alasanPindahDealer").on("change", function(e) {
              if ($("#alasanPindahDealer").val() == 4) {
                $('#input_alasan_pindah_dealer_lainnya').show();
              } else {
                $('#input_alasan_pindah_dealer_lainnya').hide();
              }
            })
          </script>
        </div>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-sm-12 col-md-12" align='center'>
            <button type="button" class="btn btn-primary" onclick="saveAssignDealer(this)">Assigned Dealer</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
  <?php
  $data['data'] = ['selectAlasanPindahDealer'];
  $this->load->view('additionals/dropdown_assigned_reassigned', $data);
  ?>
</div>
<div class="modal fade" id="modalReAssign">
  <div class="modal-dialog" style='width:90%'>
    <div class="modal-content">
      <div class="modal-header bg-red disabled color-palette">
        <button style='color:white' type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" align='center'>Reassign Dealer</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-default box-solid">
              <div class="box-header with-border">
                <h3 class="box-title">Dispatch History</h3>
                <div class="box-tools pull-right">
                  <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                  </button> -->
                </div>
                <!-- /.box-tools -->
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <table class='table table-bordered table-condensed table-striped table-hover' id="tbl_dispatch_history" style='width:100%'>
                  <thead>
                    <th>#</th>
                    <th>Nama Dealer</th>
                    <th>Tgl. Dispatch</th>
                    <th>Alasan Reassign</th>
                    <th>Tgl. FU 1</th>
                    <th>SLA FU1 D</th>
                    <th>D Overdue</th>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="box box-default box-solid">
              <div class="box-header with-border">
                <h3 class="box-title">Main Dealer/Dealer Leads Distribution Checkbox*</h3>
                <div class="box-tools pull-right">
                  <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                  </button> -->
                </div>
                <!-- /.box-tools -->
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <div class="form-horizontal">
                  <div class="form-group">
                    <div class="form-input">
                      <div class="col-sm-2">
                        <input type="checkbox" class="flat-red" name="reassign_territory_data" id='reassign_territory_data' checked> Territory Data
                      </div>
                      <div class="col-sm-2">
                        <input type="checkbox" class="flat-red" name="reassign_dealer_mapping" id='reassign_dealer_mapping' checked> Dealer Mapping
                      </div>
                      <div class="col-sm-2">
                        <input type="checkbox" class="flat-red" name="reassign_nos_score" id='reassign_nos_score' checked> NOS Score
                      </div>
                      <div class="col-sm-3">
                        <input type="checkbox" class="flat-red" name="reassign_dealer_crm_score" id='reassign_dealer_crm_score' checked> Dealer CRM Score
                      </div>
                      <div class="col-sm-3">
                        <input type="checkbox" class="flat-red" name="reassign_workload_dealer" id='reassign_workload_dealer' checked> Workload Dealer
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-6 control-label">Threshold actual avg assigned leads per salespeople : </label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" name='reassign_threshold_per_salespeople' id='reassign_threshold_per_salespeople'>
                      </div>
                    </div>
                  </div>
                </div>
                <div style='font-size:10pt'>* Jika tidak menemukan kondisi ideal, boleh melakukan uncheck dari prioritas terbawah, yaitu dari kanan (Workload Dealer) ke kiri (Territory Data)</div>
              </div>
              <div class="box-footer" align='center'>
                <button class='btn btn-primary' type="button" onclick="searchReAssignDealer()">Filter</button>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="box box-default box-solid">
              <div class="box-header with-border">
                <h3 class="box-title">Hasil Filter Rekomendasi Dealer</h3>
                <!-- /.box-tools -->
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <div class="table-responsive">
                  <table class='table table-condensed table-stiped table-bordered' id='tbl_reassign_dealer' style="width:100%">
                    <thead>
                      <th>No.</th>
                      <th>Kode Dealer</th>
                      <th>Nama Dealer</th>
                      <th>Territory</th>
                      <th>Channel Mapping</th>
                      <th>NOS Score</th>
                      <th>CRM Score</th>
                      <th>Sales People On Duty</th>
                      <th>Workload</th>
                      <th>Workload Capacity</th>
                      <th>Action</th>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
            <!-- /.box -->
          </div>
        </div>
        <div class="row">
          <form id='form_alasan_reassign'>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="alasan_reassign">Alasan Reassign Dealer *</label>
                <div class="form-input">
                  <select style='width:100%' id="alasanReAssignDealer" class='form-control' name='alasanReAssignDealer' required>
                    <option value=''>- Pilih -</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-sm-6" style="display:none" id="input_alasanReAssignDealerLainnya">
              <div class="form-group">
                <label>Alasan Reassigned Dealer Lainnya *</label>
                <div class="form-input">
                  <input type="text" class="form-control" id="alasanReAssignDealerLainnya" name='alasanReAssignDealerLainnya' required>
                </div>
              </div>
            </div>
          </form>
          <script>
            $("#alasanReAssignDealer").on("change", function(e) {
              if ($("#alasanReAssignDealer").val() == 4) {
                $('#input_alasanReAssignDealerLainnya').show();
              } else {
                $('#input_alasanReAssignDealerLainnya').hide();
              }
            })
          </script>
        </div>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-sm-12 col-md-12" align='center'>
            <button type="button" class="btn btn-success" onclick="saveReAssignDealer(this)">Reassign Dealer</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
  <?php
  $data['data'] = ['selectAlasanReassign'];
  $this->load->view('additionals/dropdown_assigned_reassigned', $data);
  ?>
</div>

<script>
  // Assign
  $('#workload_dealer').on('ifChecked', function(el) {
    $('#threshold_per_salespeople').attr('disabled', false);
    $('#threshold_per_salespeople').attr('required', 'required');
    $('#threshold_per_salespeople').val('');
    $('#threshold_per_salespeople_label').html('*');
  })
  $('#workload_dealer').on('ifUnchecked', function(el) {
    $('#threshold_per_salespeople').removeAttr('required');
    $('#threshold_per_salespeople_label').html('');
    $('#threshold_per_salespeople').attr('disabled', true);
    $('#threshold_per_salespeople').val('');
  })

  var assignedDealer = '';

  function setAssignDealer(el, kode_dealer) {
    $('.btnAssignDealer').removeClass('btn-success');
    $('.btnAssignDealer').addClass('btn-primary');
    $(el).removeClass('btn-primary');
    $(el).addClass('btn-success');
    $('tr').removeClass('bg-success');
    $(el).closest('tr').addClass('bg-success');
    assignedDealer = kode_dealer;
    cekBedaDealerPembelianDenganAssignDealer();
  }

  function cekBedaDealerPembelianDenganAssignDealer() {
    kodeDealerPembelianSebelumnya = $('#modal_assg_kodeDealerPembelianSebelumnya').val();
    if (kodeDealerPembelianSebelumnya != '' && kodeDealerPembelianSebelumnya != assignedDealer) {
      $('#form_alasan_pindah_dealer').show();
    } else {
      $('#form_alasan_pindah_dealer').hide();
    }
  }

  function searchAssignDealer() {
    let workload = $("#workload_dealer").prop("checked");
    if (workload == true) {
      let threshold = $('#threshold_per_salespeople').val();
      if (threshold == '') {
        Swal.fire({
          icon: 'error',
          title: '<font color="white">Peringatan</font>',
          html: '<font color="white">Silahkan Tentukan Threshold actual avg assigned leads per salespeople</font>',
          background: '#dd4b39',
          confirmButtonColor: '#cc3422',
          confirmButtonText: 'Tutup',
          iconColor: 'white'
        });
        return false;
      }
    }
    $('#tbl_assign_dealer').DataTable().ajax.reload();
  }

  function saveAssignDealer(el) {
    if (assignedDealer == '') {
      Swal.fire({
        icon: 'error',
        title: '<font color="white">Peringatan</font>',
        html: '<font color="white">Silahkan pilih dealer terlebih dahulu</font>',
        background: '#dd4b39',
        confirmButtonColor: '#cc3422',
        confirmButtonText: 'Tutup',
        iconColor: 'white'
      });
      return false;
    }
    $('#form_alasan_pindah_dealer').validate({
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
    if ($('#form_alasan_pindah_dealer').valid()) // check if form is valid
    {
      Swal.fire({
        title: 'Assigned To Dealer',
        text: 'Apakah Anda yakin melakukan Assigned ke Dealer : ' + assignedDealer + ' ?',
        showCancelButton: true,
        confirmButtonText: 'Assigned Dealer',
        cancelButtonText: 'Batal',
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          var values = {
            assignedDealer: assignedDealer,
            leads_id: leads_id,
            alasanPindahDealer: $('#alasanPindahDealer').val(),
            alasanPindahDealerLainnya: $('#alasanPindahDealerLainnya').val(),
          }
          $.ajax({
            beforeSend: function() {
              $(el).html('<i class="fa fa-spinner fa-spin"></i> Process');
              $(el).attr('disabled', true);
            },
            enctype: 'multipart/form-data',
            url: '<?= site_url(get_controller() . '/saveAssignDealer') ?>',
            type: "POST",
            data: values,
            // processData: false,
            // contentType: false,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              if (response.status == 1) {
                location.reload(true);
              } else {
                Swal.fire({
                  icon: 'error',
                  title: '<font color="white">Peringatan</font>',
                  html: '<font color="white">' + response.pesan + '</font>',
                  background: '#dd4b39',
                  confirmButtonColor: '#cc3422',
                  confirmButtonText: 'Tutup',
                  iconColor: 'white'
                })
                $(el).attr('disabled', false);
              }
              $(el).html('Assigned Dealer');
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
              $(el).html('Simpan');
              $(el).attr('disabled', false);
            }
          });
        } else if (result.isDenied) {
          // Swal.fire('Changes are not saved', '', 'info')
        }
      })
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



  //Reassign
  $('#reassign_workload_dealer').on('ifChecked', function(el) {
    $('#reassign_threshold_per_salespeople').attr('disabled', false);
    $('#reassign_threshold_per_salespeople').val('');
  })
  $('#reassign_workload_dealer').on('ifUnchecked', function(el) {
    $('#reassign_threshold_per_salespeople').attr('disabled', true);
    $('#reassign_threshold_per_salespeople').val('');
  })

  var reAssignedDealer = '';

  function setReAssignDealer(el, kode_dealer) {
    $('.btnReAssignDealer').removeClass('btn-success');
    $('.btnReAssignDealer').addClass('btn-primary');
    $(el).removeClass('btn-primary');
    $(el).addClass('btn-success');
    $('tr').removeClass('bg-success');
    $(el).closest('tr').addClass('bg-success');
    reAssignedDealer = kode_dealer;
  }

  function searchReAssignDealer() {
    let workload = $("#reassign_workload_dealer").prop("checked");
    if (workload == true) {
      let threshold = $('#reassign_threshold_per_salespeople').val();
      if (threshold == '') {
        Swal.fire({
          icon: 'error',
          title: '<font color="white">Peringatan</font>',
          html: '<font color="white">Silahkan Tentukan Threshold actual avg assigned leads per salespeople</font>',
          background: '#dd4b39',
          confirmButtonColor: '#cc3422',
          confirmButtonText: 'Tutup',
          iconColor: 'white'
        });
        return false;
      }
    }
    $('#tbl_reassign_dealer').DataTable().ajax.reload();
  }

  function saveReAssignDealer(el) {
    if (reAssignedDealer == '') {
      Swal.fire({
        icon: 'error',
        title: '<font color="white">Peringatan</font>',
        html: '<font color="white">Silahkan pilih dealer terlebih dahulu</font>',
        background: '#dd4b39',
        confirmButtonColor: '#cc3422',
        confirmButtonText: 'Tutup',
        iconColor: 'white'
      });
      return false;
    }
    $('#form_alasan_reassign').validate({
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
    if ($('#form_alasan_reassign').valid()) // check if form is valid
    {
      Swal.fire({
        title: 'Reassigned To Dealer',
        text: 'Apakah Anda yakin melakukan Ressigned ke Dealer : ' + reAssignedDealer + ' ?',
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal',
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          var values = {
            assignedDealer: reAssignedDealer,
            alasanReAssignDealer: $('#alasanReAssignDealer').val(),
            alasanReAssignDealerLainnya: $('#alasanReAssignDealerLainnya').val(),
            leads_id: leads_id
          }
          $.ajax({
            beforeSend: function() {
              $(el).html('<i class="fa fa-spinner fa-spin"></i> Process');
              $(el).attr('disabled', true);
            },
            enctype: 'multipart/form-data',
            url: '<?= site_url(get_controller() . '/saveReAssignDealer') ?>',
            type: "POST",
            data: values,
            // processData: false,
            // contentType: false,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              if (response.status == 1) {
                location.reload(true);
              } else {
                Swal.fire({
                  icon: 'error',
                  title: '<font color="white">Peringatan</font>',
                  html: '<font color="white">' + response.pesan + '</font>',
                  background: '#dd4b39',
                  confirmButtonColor: '#cc3422',
                  confirmButtonText: 'Tutup',
                  iconColor: 'white'
                })
                $(el).attr('disabled', false);
              }
              $(el).html('Reassign Dealer');
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
              $(el).html('Reassign Dealer');
              $(el).attr('disabled', false);
            }
          });
        } else if (result.isDenied) {
          // Swal.fire('Changes are not saved', '', 'info')
        }
      })
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