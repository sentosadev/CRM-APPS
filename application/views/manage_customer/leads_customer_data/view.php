<script>
  $(document).ready(function() {
    $('.select2').select2();
    setButtonDetailKesalahanTerakhir();
  })
</script>
<style>
  .padding-td {
    padding-right: 5px;
    text-align: center;
    width: 10%;
  }

  .bg-gray-selected {
    background-color: #6a6b6d !important;
  }
</style>
<section class="content">
  <!-- SELECT2 EXAMPLE -->
  <div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
      <table style="width:100%">
        <tbody>
          <tr>
            <td class="padding-td">
              <div class="small-box bg-gray" onclick="setFilterBelumFUMD()" id="filterBelumFUMD">
                <div class="inner" style="padding-bottom:0px" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="">
                  <p style=' min-height:65px'>Leads belum FU MD (workload) <br>&nbsp;</p>
                  <h3 class="card_view" id="data_source"><?= $mon['belum_fu_md'] ?></h3>
                </div>
                <div class="card_view_persen small-box-footer" style="color:black;font-weight:bold" id="data_source_persen"></div>
              </div>
            </td>
            <td class="padding-td">
              <div class="small-box bg-gray" onclick="setLeadsNeedFU()" id="leadsNeedFU">
                <div class="inner" style="padding-bottom:0px" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="">
                  <p style='min-height:65px'>Leads Need FU <br>&nbsp;</p>
                  <h3 class="card_view" id="data_source"><?= $mon['need_fu'] ?></h3>
                </div>
                <div class="card_view_persen small-box-footer" style="color:black;font-weight:bold" id="data_source_persen"></div>
              </div>
            </td>
            <td class="padding-td">
              <div class="small-box bg-gray" onclick="setBelumAssignDealer()" id="belumAssignDealer">
                <div class="inner" style="padding-bottom:0px" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="">
                  <p style='min-height:65px'>Leads belum Assign Dealer <br>&nbsp;</p>
                  <h3 class="card_view" id="data_source"><?= $mon['belum_assign_dealer'] ?></h3>
                </div>
                <div class="card_view_persen small-box-footer" style="color:black;font-weight:bold" id="data_source_persen"></div>
              </div>
            </td>
            <td class="padding-td">
              <div class="small-box bg-gray" onclick="setMelewatiSLAMD()" id="melewatiSLAMD">
                <div class="inner" style="padding-bottom:0px" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="">
                  <p style='min-height:65px'>Leads yang melewati SLA MD <br>&nbsp;</p>
                  <h3 class="card_view" id="data_source"><?= $mon['lewat_sla_md'] ?></h3>
                </div>
                <div class="card_view_persen small-box-footer" style="color:black;font-weight:bold" id="data_source_persen"></div>
              </div>
            </td>
            <td class="padding-td">
              <div class="small-box bg-gray" onclick="setMelewatiSLADealer()" id="melewatiSLADealer">
                <div class="inner" style="padding-bottom:0px" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="">
                  <p style='min-height:65px'>Leads yang melewati SLA D <br>&nbsp;</p>
                  <h3 class="card_view" id="data_source"><?= $mon['lewat_sla_d'] ?></h3>
                </div>
                <div class="card_view_persen small-box-footer" style="color:black;font-weight:bold" id="data_source_persen"></div>
              </div>
            </td>
            <td class="padding-td">
              <div class="small-box bg-gray" onclick="setLeadsMultiInteraction()" id="leadsMultiInteraction">
                <div class="inner" style="padding-bottom:0px" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="">
                  <p style='min-height:65px'>Leads Multi-Interaction <br>&nbsp;</p>
                  <h3 class="card_view" id="data_source"><?= $mon['multi_interaksi'] ?></h3>
                </div>
                <div class="card_view_persen small-box-footer" style="color:black;font-weight:bold" id="data_source_persen"></div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="box box-default">
    <div class="box-header with-border">
      <a class="btn btn-primary btn-flat" href="<?= site_url(get_controller() . '/download_leads_non_ve') ?>"><i class='fa fa-download'></i> Download Leads Non-VE</a>
      <button class="btn btn-info btn-flat" onclick="showModalUploadLeadsNonVE()"><i class='fa fa-upload'></i> Upload Leads Non-VE</button>
      <button id="btnDetailKesalahanUploadTerakhir" class="btn btn-danger btn-flat" onclick="showModalErrorUploads()"><i class="fa fa-cross"></i> Detail Kesalahan Upload Terakhir</button>
      <div class="box-tools pull-right">
        <?= link_on_data_top(user()->id_group); ?>
      </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-danger box-solid collapsed-box">
            <div class="box-header with-border">
              <h3 class="box-title">Search</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form class="form-horizontal" id="form_search_leads">
                <div class="form-group">
                  <label class="col-sm-2 control-label">Leads ID</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id=' leads_id' multiple>
                      </select>
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Periode Event</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id='periode_event' name='periode_event'>
                      <input type="hidden" class="form-control" id='start_periode_event'>
                      <input type="hidden" class="form-control" id='end_periode_event'>
                    </div>
                  </div>
                  <script>
                    $(function() {
                      $('#periode_event').daterangepicker({
                        // opens: 'left',
                        autoUpdateInput: false,
                        locale: {
                          format: 'DD/MM/YYYY'
                        }
                      }, function(start, end, label) {
                        $('#start_periode_event').val(start.format('YYYY-MM-DD'));
                        $('#end_periode_event').val(end.format('YYYY-MM-DD'));
                      }).on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                      }).on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');
                        $('#start_periode_event').val('');
                        $('#end_periode_event').val('');
                      });
                    });
                  </script>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">No. HP</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <input class='form-control' id='noHP' onkeypress="only_number(event)">
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Status FU</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id='id_status_fu' multiple>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Dealer Sebelumnya</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id='kodeDealerSebelumnya' multiple>
                      </select>
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Hasil FU</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id='kodeHasilStatusFollowUp' multiple>
                        <option value=''>- Pilih -</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Dealer Dispatch</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id='searchAssignedDealer' multiple>
                      </select>
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Jumlah FU</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id='jumlah_fu' multiple>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Platform Data</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id='id_platform_data' multiple>
                      </select>
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Next FU</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id='next_fu' name='next_fu'>
                      <input type="hidden" class="form-control" id='start_next_fu'>
                      <input type="hidden" class="form-control" id='end_next_fu'>
                    </div>
                  </div>
                  <script>
                    $(function() {
                      $('#next_fu').daterangepicker({
                        // opens: 'left',
                        autoUpdateInput: false,
                        locale: {
                          format: 'DD/MM/YYYY'
                        }
                      }, function(start, end, label) {
                        $('#start_next_fu').val(start.format('YYYY-MM-DD'));
                        $('#end_next_fu').val(end.format('YYYY-MM-DD'));
                      }).on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                      }).on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');
                        $('#start_next_fu').val('');
                        $('#end_next_fu').val('');
                      });
                    });
                  </script>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Source Leads</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id='id_source_leads' multiple>
                      </select>
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Tipe Motor</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id='id_tipe' multiple>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Deskripsi Event</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id='deskripsiEvent' multiple>
                      </select>
                    </div>
                  </div>
                  <label class="col-sm-2 control-label">Overdue D</label>
                  <div class="form-input">
                    <div class="col-sm-4">
                      <select class="form-control select2" style="width: 100%;" id='ontimeSLA2_multi' multiple>
                        <option value=''>- Pilih -</option>
                        <option value='1'>On Track</option>
                        <option value='0'>Overdue</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Tampilkan Hasil FU Not Prospect</label>
                  <div class="form-input">
                    <div class="form-input">
                      <div class="col-sm-1">
                        <input type="radio" name="show_hasil_fu_not_prospect" value="1" class="flat-red" style="position: absolute; opacity: 0;"> Ya
                      </div>
                      <div class="col-sm-3">
                        <input type="radio" name="show_hasil_fu_not_prospect" value="0" class="flat-red" style="position: absolute; opacity: 0;" checked> Tidak
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="box-footer" align='center'>
              <button class='btn btn-primary' type="button" onclick="search()"><i class="fa fa-search"></i></button>
              <button class='btn btn-default' type="button" onclick="location.reload(true)"><i class="fa fa-refresh"></i></button>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
      <div class="table-responsive">
        <table class='table table-condensed table-bordered table-striped serverside-tables' style="width:100%">
          <thead>
            <th width='5%'>#</th>
            <th width='15%'>Leads ID</th>
            <th>Nama</th>
            <th>Dealer Sebelumnya</th>
            <th>Dealer Dispatch</th>
            <th>Tgl. Dispatch</th>
            <th>Platform Data</th>
            <th>Source Leads</th>
            <th>Deskripsi Event</th>
            <th>Periode Event</th>
            <th>Status FU</th>
            <th>Pernah Terhubung</th>
            <th>Hasil FU</th>
            <th>Jumlah FU</th>
            <th>Next FU</th>
            <th>Last Update</th>
            <th>MD Overdue</th>
            <th>D Overdue</th>
            <th>Action</th>
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
<?php $data['data'] = ['selectLeadsId', 'selectStatusFU', 'selectPlatformData', 'selectSourceLeads', 'selectDealerSebelumnya', 'selectAssignedDealer', 'selectDeskripsiEvent', 'selectJumlahFu', 'selectHasilStatusFollowUp'];
$this->load->view('additionals/dropdown_search_menu_leads_customer_data', $data); ?>

<?php $data['data'] = ['selectTipe'];
$this->load->view('additionals/dropdown_series_tipe', $data); ?>

<?php $this->load->view('manage_customer/leads_customer_data/modal_assign_reassign'); ?>
<?php $this->load->view('manage_customer/leads_customer_data/modal_history'); ?>
<?php $this->load->view('manage_customer/leads_customer_data/modal_upload_leads_non_ve'); ?>

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
          d.noHP = $('#noHP').val()
          d.kode_type_motor_multi = $('#id_tipe').val()
          d.leads_id_multi = $('#leads_id').val()
          d.deskripsi_event_multi = $('#deskripsiEvent').val()
          d.id_status_fu_multi = $('#id_status_fu').val()
          d.jumlah_fu = $('#jumlah_fu').val()
          d.start_next_fu = $('#start_next_fu').val()
          d.end_next_fu = $('#end_next_fu').val()
          d.kodeHasilStatusFollowUpMulti = $('#kodeHasilStatusFollowUp').val()
          d.ontimeSLA2_multi = $('#ontimeSLA2_multi').val()
          d.show_hasil_fu_not_prospect = $('input[name=show_hasil_fu_not_prospect]:checked', '#form_search_leads').val()
          d.start_periode_event = $('#start_periode_event').val()
          d.end_periode_event = $('#end_periode_event').val()
          d.filterBelumFUMD = filterBelumFUMD;
          d.leadsNeedFU = leadsNeedFU;
          d.belumAssignDealer = belumAssignDealer;
          d.melewatiSLAMD = melewatiSLAMD;
          d.melewatiSLADealer = melewatiSLADealer;
          d.leadsMultiInteraction = leadsMultiInteraction;
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

  var load_data_assign_dealer = 0;

  var leads_id = '';

  function showAssign(ld_id) {
    $('#form_alasan_pindah_dealer').hide();
    $('#alasanPindahDealer').val('');
    $('#alasanPindahDealerLainnya').val('');
    leads_id = ld_id;
    setLeadsAssignReassign(leads_id, 'assg');
    $('#modalAssign').modal('show');
    if (load_data_assign_dealer == 0) {
      var dataTable = $('#tbl_assign_dealer').DataTable({
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
          url: "<?php echo site_url(get_controller() . '/fetchAssignDealer'); ?>",
          type: "POST",
          dataSrc: "data",
          data: function(d) {
            d.territory_data = $("#territory_data").prop("checked");
            d.dealer_mapping = $("#dealer_mapping").prop("checked");
            d.nos_score = $("#nos_score").prop("checked");
            d.dealer_crm_score = $("#dealer_crm_score").prop("checked");
            d.workload_dealer = $("#workload_dealer").prop("checked");
            d.threshold_per_salespeople = $("#threshold_per_salespeople").val();
            d.leads_id = leads_id;
            return d;
          },
        },
        "columnDefs": [{
            "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            "orderable": false
          },
          {
            "targets": [10],
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
    } else {
      $('#tbl_assign_dealer').DataTable().ajax.reload();

    }
    load_data_assign_dealer++;
  }

  var load_data_reassign_dealer = '';

  function showReAssign(ld_id) {
    leads_id = ld_id;
    $('#modalReAssign').modal('show');
    if (load_data_reassign_dealer == 0) {
      var dataTable = $('#tbl_reassign_dealer').DataTable({
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
          url: "<?php echo site_url(get_controller() . '/fetchReAssignDealer'); ?>",
          type: "POST",
          dataSrc: "data",
          data: function(d) {
            d.territory_data = $("#reassign_territory_data").prop("checked");
            d.dealer_mapping = $("#reassign_dealer_mapping").prop("checked");
            d.nos_score = $("#reassign_nos_score").prop("checked");
            d.dealer_crm_score = $("#reassign_dealer_crm_score").prop("checked");
            d.workload_dealer = $("#reassign_workload_dealer").prop("checked");
            d.threshold_per_salespeople = $("#reassign_threshold_per_salespeople").val();
            d.leads_id = leads_id;
            return d;
          },
        },
        "columnDefs": [{
            "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            "orderable": false
          },
          {
            "targets": [10],
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

      var dataTable = $('#tbl_dispatch_history').DataTable({
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
          url: "<?php echo site_url(get_controller() . '/fetchDispatchHistory'); ?>",
          type: "POST",
          dataSrc: "data",
          data: function(d) {
            d.leads_id = leads_id;
            return d;
          },
        },
        "columnDefs": [{
            "targets": [0, 1, 2, 3, 4, 5, 6],
            "orderable": false
          },
          {
            "targets": [6],
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
    } else {
      $('#tbl_dispatch_history').DataTable().ajax.reload();
      $('#tbl_reassign_dealer').DataTable().ajax.reload();
    }
    load_data_reassign_dealer++;
  }

  function search() {
    $('.serverside-tables').DataTable().ajax.reload();
  }

  function setLeadsAssignReassign(id, assg_reassg) {
    values = {
      leads_id: id
    }
    $.ajax({
      beforeSend: function() {
        $('#modal_' + assg_reassg + '_leads_id').val('');
        $('#modal_' + assg_reassg + '_nama').val('');
        $('#modal_' + assg_reassg + '_namaDealerPembelianSebelumnya').val('');
        $('#modal_' + assg_reassg + '_kodeDealerPembelianSebelumnya').val('');
      },
      enctype: 'multipart/form-data',
      url: '<?= site_url(get_controller() . '/getLeadsByLeadsId') ?>',
      type: "POST",
      data: values,
      // processData: false,
      // contentType: false,
      cache: false,
      dataType: 'JSON',
      success: function(response) {
        if (response.status == 1) {
          data = response.data;
          $('#modal_' + assg_reassg + '_leads_id').val(data.leads_id);
          $('#modal_' + assg_reassg + '_nama').val(data.nama);
          $('#modal_' + assg_reassg + '_namaDealerPembelianSebelumnya').val(data.namaDealerPembelianSebelumnya);
          $('#modal_' + assg_reassg + '_kodeDealerPembelianSebelumnya').val(data.kodeDealerPembelianSebelumnya);
          $('#modal_' + assg_reassg + '_deskripsiKecamatanDomisili').val(data.deskripsiKecamatanDomisili);
          $('#modal_' + assg_reassg + '_deskripsiKabupatenKotaDomisili').val(data.deskripsiKabupatenKotaDomisili);
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
        }
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
      }
    });
  }

  var filterBelumFUMD = false;

  function setFilterBelumFUMD() {
    if (filterBelumFUMD == false) {
      filterBelumFUMD = true;
    } else {
      filterBelumFUMD = false;
    }
    resetAllHeader('filterBelumFUMD', filterBelumFUMD);
    search();
  }

  var leadsNeedFU = false;

  function setLeadsNeedFU() {
    if (leadsNeedFU == false) {
      leadsNeedFU = true;
    } else {
      leadsNeedFU = false;
    }
    resetAllHeader('leadsNeedFU', leadsNeedFU);
    search();
  }

  var belumAssignDealer = false;

  function setBelumAssignDealer() {
    if (belumAssignDealer == false) {
      belumAssignDealer = true;
    } else {
      belumAssignDealer = false;
    }
    resetAllHeader('belumAssignDealer', belumAssignDealer);
    search();
  }

  var melewatiSLAMD = false;

  function setMelewatiSLAMD() {
    if (melewatiSLAMD == false) {
      melewatiSLAMD = true;
    } else {
      melewatiSLAMD = false;
    }
    resetAllHeader('melewatiSLAMD', melewatiSLAMD);
    search();
  }

  var melewatiSLADealer = false;

  function setMelewatiSLADealer() {
    if (melewatiSLADealer == false) {
      melewatiSLADealer = true;
    } else {
      melewatiSLADealer = false;
    }
    resetAllHeader('melewatiSLADealer', melewatiSLADealer);
    search();
  }

  var leadsMultiInteraction = false;

  function setLeadsMultiInteraction() {
    if (leadsMultiInteraction == false) {
      leadsMultiInteraction = true;
    } else {
      leadsMultiInteraction = false;
    }
    resetAllHeader('leadsMultiInteraction', leadsMultiInteraction);
    search();
  }

  function resetAllHeader(except, val) {
    $('.small-box').removeClass('bg-gray-selected');
    $('.small-box').addClass('bg-gray');

    filterBelumFUMD = false;
    leadsNeedFU = false;
    belumAssignDealer = false;
    melewatiSLAMD = false;
    melewatiSLADealer = false;
    leadsMultiInteraction = false;
    if (except == 'filterBelumFUMD') {
      filterBelumFUMD = val;
      if (val == true) {
        $('#filterBelumFUMD').addClass('bg-gray-selected');
      }
    }

    if (except == 'leadsNeedFU') {
      leadsNeedFU = val;
      if (val == true) {
        $('#leadsNeedFU').addClass('bg-gray-selected');
      }
    }

    if (except == 'belumAssignDealer') {
      belumAssignDealer = val;
      if (val == true) {
        $('#belumAssignDealer').addClass('bg-gray-selected');
      }
    }

    if (except == 'melewatiSLAMD') {
      melewatiSLAMD = val;
      if (val == true) {
        $('#melewatiSLAMD').addClass('bg-gray-selected');
      }
    }

    if (except == 'melewatiSLADealer') {
      melewatiSLADealer = val;
      if (val == true) {
        $('#melewatiSLADealer').addClass('bg-gray-selected');
      }
    }

    if (except == 'leadsMultiInteraction') {
      leadsMultiInteraction = val;
      if (val == true) {
        $('#leadsMultiInteraction').addClass('bg-gray-selected');
      }
    }
  }
</script>
<?php $this->load->view('additionals/modal_errors'); ?>
<script>
  var set_errors = [];
</script>