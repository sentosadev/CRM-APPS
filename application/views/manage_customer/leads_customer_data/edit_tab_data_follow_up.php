<?php
$total_fol_up = count($list_follow_up) == 0 ? 1 : count($list_follow_up);
$max_pertab = 2;
$fol_up_sekarang = 1;
$hide_tambah=false;
if (isset($list_follow_up[$total_fol_up])) {
  if ($list_follow_up[$total_fol_up]['tglFollowUpFormated']=='') {
    $hide_tambah=true;
  }
}
for ($i = 1; $i <= $tot_tab_fol; $i++) {
  if ($i == 1) {
    $back = 'data_pendukung_probing_1';
  } else {
    $to = $i - 1;
    $back = "data_follow_up_$to";
  }
?>
  <div class="tab-pane" id="data_follow_up_<?= $i ?>">
    <?php $data = ['set_active' => [1, 2, 3]];
    $this->load->view('manage_customer/leads_customer_data/wizard', $data); ?>
    <label data-toggle="tooltip" data-html="true" data-html="true" class='tooltipInformasiCustomer'><i>Informasi</i> <i class='fa fa-info-circle'></i></label>
    <form id="form_data_follow_up_<?= $i ?>" class='form-horizontal form_'>
      <div class="row">

        <?php
        $set_disabled = 'disabled';
        for ($x = 1; $x <= $max_pertab; $x++) {
          if (!isset($list_follow_up[$fol_up_sekarang])) continue;
          $is_md = 0;
          if (isset($list_follow_up[$fol_up_sekarang])) {
            if ($list_follow_up[$fol_up_sekarang]['assignedDealer'] == '') {
              $is_md = 1;
            } else {
              $is_md = 0;
            }
          }
          //Cek Follow Up Terakhir Apakah Harus Disabled
          if ($total_fol_up == $fol_up_sekarang) {
            $fol = $list_follow_up[$fol_up_sekarang];
            if ((string)$fol['pic'] == '' && (string)$fol['tglFollowUpFormated'] == '' && (string)$fol['id_media_kontak_fu'] == '' && (string)$fol['id_status_fu'] == '') {
              $set_disabled = '';
            }
          }
        ?>
          <div class="col-sm-6">
            <div class="form-group">
              <?php if ($is_md == 1) { ?>
                <label class="col-sm-4 control-label">PIC MD *</label>
                <div class="form-input">
                  <div class="col-sm-8">
                    <select style='width:100%' id='pic_<?= $fol_up_sekarang ?>' class='form-control' name='pic_<?= $fol_up_sekarang ?>' <?= $disabled ?> required <?= $set_disabled ?>>
                      <option></option>
                      <?php
                      $list[1] = 'PIC VE MD';
                      $list[2] = 'Telemarketer HC3';
                      foreach ($list as $key => $val) {
                        $pic = isset($list_follow_up[$fol_up_sekarang]) ? $list_follow_up[$fol_up_sekarang]['pic'] : '';
                      ?>
                        <option value='<?= $key ?>' <?= $key == $pic ? 'selected' : '' ?>><?= $val ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              <?php } else { ?>
                <label class="col-sm-4 control-label">PIC Dealer *</label>
                <div class="form-input">
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name='pic_<?= $fol_up_sekarang ?>' required value='<?= isset($list_follow_up[$fol_up_sekarang]) ? $list_follow_up[$fol_up_sekarang]['pic'] : '' ?>' <?= $disabled ?> <?= $set_disabled ?>>
                  </div>
                </div>
              <?php } ?>
              <input type="hidden" class="form-control" name='folup[]' required value='<?= $fol_up_sekarang ?>'>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">Tanggal Follow Up <?= $fol_up_sekarang ?> *</label>
              <div class="form-input">
                <div class="col-sm-8">
                  <input type="text" class="form-control" id='tglFollowUp_<?= $fol_up_sekarang ?>' name='tglFollowUp_<?= $fol_up_sekarang ?>' required value='<?= isset($list_follow_up[$fol_up_sekarang]) ? $list_follow_up[$fol_up_sekarang]['tglFollowUpFormated'] == '' ? dMYHIS_en() : $list_follow_up[$fol_up_sekarang]['tglFollowUpFormated'] : dMYHIS_en() ?>' <?= $disabled ?> required <?= $set_disabled == 'disabled' ? 'readonly' : '' ?> readonly>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">Keterangan Follow Up <?= $fol_up_sekarang ?></label>
              <div class="form-input">
                <div class="col-sm-8">
                  <input type="text" class="form-control" id='keteranganFollowUp_<?= $fol_up_sekarang ?>' name='keteranganFollowUp_<?= $fol_up_sekarang ?>' value='<?= isset($list_follow_up[$fol_up_sekarang]) ? $list_follow_up[$fol_up_sekarang]['keteranganFollowUp'] : '' ?>' <?= $disabled ?> <?= $set_disabled ?>>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">Media Komunikasi Fol. Up <?= $fol_up_sekarang ?> *</label>
              <div class="form-input">
                <div class="col-sm-8">
                  <select style="width:100%" id="id_media_kontak_fu_<?= $fol_up_sekarang ?>" class='form-control' name='id_media_kontak_fu_<?= $fol_up_sekarang ?>' <?= $disabled ?> required <?= $set_disabled ?>>
                    <?php if (isset($list_follow_up[$fol_up_sekarang])) {
                      $lfu = $list_follow_up[$fol_up_sekarang]; ?>
                      <option value='<?= $lfu['id_media_kontak_fu'] ?>'><?= $lfu['media_kontak_fu'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">Status Komunikasi Fol. Up <?= $fol_up_sekarang ?> *</label>
              <div class="form-input">
                <div class="col-sm-8">
                  <select style="width:100%" id="id_status_fu_<?= $fol_up_sekarang ?>" class='form-control' name='id_status_fu_<?= $fol_up_sekarang ?>' <?= $disabled ?> required <?= $set_disabled ?>>
                    <?php if (isset($list_follow_up[$fol_up_sekarang])) {
                      $lfu = $list_follow_up[$fol_up_sekarang]; ?>
                      <option value='<?= $lfu['id_status_fu'] ?>'><?= $lfu['status_fu'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">Tgl. Next Follow Up <span id="tglNextFollowUpLabel_<?= $fol_up_sekarang ?>"></span></label>
              <div class="form-input">
                <div class="col-sm-8">
                  <input type="text" class="form-control datepicker" id='tglNextFollowUp_<?= $fol_up_sekarang ?>' name='tglNextFollowUp_<?= $fol_up_sekarang ?>' value='<?= isset($list_follow_up[$fol_up_sekarang]) ? $list_follow_up[$fol_up_sekarang]['tglNextFollowUp'] : '' ?>' <?= $disabled ?> <?= $set_disabled ?>>
                </div>
              </div>
            </div>
            <script>
              var statusProspek = '<?= isset($list_follow_up[$fol_up_sekarang]) ? $list_follow_up[$fol_up_sekarang]['statusProspek'] : '' ?>';
              $('#tglNextFollowUp_<?= $fol_up_sekarang ?>').on('apply.daterangepicker', function(ev, picker) {
                let tgl_folup = $('#tglFollowUp_<?= $fol_up_sekarang ?>').val();
                let new_dt = new Date(tgl_folup);
                var date = new_dt.getDate();
                var month = new_dt.getMonth(); //Be careful! January is 0 not 1
                var year = new_dt.getFullYear();
                tgl_folup = (month + 1) + "-" + date + "-" + year;

                let tgl_next = picker.startDate.format('YYYY-MM-DD');
                let next = new Date(tgl_next);
                var date = next.getDate();
                var month = next.getMonth(); //Be careful! January is 0 not 1
                var year = next.getFullYear();
                tgl_next = (month + 1) + "-" + date + "-" + year;
                selisih = hitungSelisihHari(tgl_folup, tgl_next);
                if (selisih < 14) {
                  statusProspek = 'Hot';
                } else if (selisih >= 14 && selisih <= 28) {
                  statusProspek = 'Medium';
                } else if (selisih > 28) {
                  statusProspek = 'Low';
                }
                $('#statusProspek_<?= $fol_up_sekarang ?>').val(statusProspek);
              });

              function hitungSelisihHari(tgl1, tgl2) {
                // varibel miliday sebagai pembagi untuk menghasilkan hari
                var miliday = 24 * 60 * 60 * 1000;
                //buat object Date
                var tanggal1 = new Date(tgl1);
                var tanggal2 = new Date(tgl2);
                // Date.parse akan menghasilkan nilai bernilai integer dalam bentuk milisecond
                var tglPertama = Date.parse(tanggal1);
                var tglKedua = Date.parse(tanggal2);
                var selisih = (tglKedua - tglPertama) / miliday;
                return selisih;
              }
            </script>
            <div class="form-group">
              <label class="col-sm-4 control-label">Status Prospek <span id="inputStatusProspek_<?= $fol_up_sekarang ?>"></span></label>
              <div class="form-input">
                <div class="col-sm-8">
                  <input type="text" class="form-control" id='statusProspek_<?= $fol_up_sekarang ?>' value='<?= isset($list_follow_up[$fol_up_sekarang]) ? $list_follow_up[$fol_up_sekarang]['statusProspek'] : '' ?>' readonly>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-4 control-label">Keterangan Next Action Follow Up</label>
              <div class="form-input">
                <div class="col-sm-8">
                  <input type="text" class="form-control" id='keteranganNextFollowUp_<?= $fol_up_sekarang ?>' name='keteranganNextFollowUp_<?= $fol_up_sekarang ?>' value='<?= isset($list_follow_up[$fol_up_sekarang]) ? $list_follow_up[$fol_up_sekarang]['keteranganNextFollowUp'] : '' ?>' <?= $disabled ?> <?= $set_disabled ?>>
                </div>
              </div>
            </div>

            <script>
              $(document).ready(function() {
                $('#input_kodeHasilStatusFollowUp_<?= $fol_up_sekarang ?>').hide();
                $('#input_kodeAlasanNotProspectNotDeal_<?= $fol_up_sekarang ?>').hide();
                $('#input_keteranganAlasanLainnya_<?= $fol_up_sekarang ?>').hide();

                //Cek ID kategori Status Komunikasi
                id_kategori_status_komunikasi = '<?= isset($list_follow_up[$fol_up_sekarang]) ? $list_follow_up[$fol_up_sekarang]['id_kategori_status_komunikasi'] : '' ?>';
                if (id_kategori_status_komunikasi == 4) {
                  $('#input_kodeHasilStatusFollowUp_<?= $fol_up_sekarang ?>').show();
                }
              })
              var $eventSelect<?= $fol_up_sekarang ?> = $("#id_status_fu_<?= $fol_up_sekarang ?>");
              $eventSelect<?= $fol_up_sekarang ?>.on("change", function(e) {
                data = $eventSelect<?= $fol_up_sekarang ?>.select2('data')[0];
                if (data != undefined) {
                  $('#kategori_status_komunikasi_<?= $fol_up_sekarang ?>').val(data.kategori);
                  if (data.idKategori == 4) {
                    $('#input_kodeHasilStatusFollowUp_<?= $fol_up_sekarang ?>').show();
                  } else {
                    $('#input_kodeHasilStatusFollowUp_<?= $fol_up_sekarang ?>').hide();
                    $('#kodeHasilStatusFollowUp_<?= $fol_up_sekarang ?>').val(null).trigger('change');
                  }
                } else {
                  $('#kategori_status_komunikasi_<?= $fol_up_sekarang ?>').val('');
                }
              });
            </script>


            <div class="form-group">
              <label class="col-sm-4 control-label">Kategori Status Komunikasi</label>
              <div class="form-input">
                <div class="col-sm-8">
                  <input type="text" class="form-control" id='kategori_status_komunikasi_<?= $fol_up_sekarang ?>' disabled value='<?= isset($list_follow_up[$fol_up_sekarang]) ? $list_follow_up[$fol_up_sekarang]['kategori_status_komunikasi'] : '' ?>' <?= $disabled ?>>
                </div>
              </div>
            </div>
            <div class="form-group" id="input_kodeHasilStatusFollowUp_<?= $fol_up_sekarang ?>">
              <label class="col-sm-4 control-label">Hasil Status Follow Up *</label>
              <div class="form-input">
                <div class="col-sm-8">
                  <select style="width:100%" id="kodeHasilStatusFollowUp_<?= $fol_up_sekarang ?>" class='form-control' name='kodeHasilStatusFollowUp_<?= $fol_up_sekarang ?>' <?= $disabled ?> required <?= $set_disabled ?>>
                    <?php if (isset($list_follow_up[$fol_up_sekarang])) {
                      $lfu = $list_follow_up[$fol_up_sekarang]; ?>
                      <option value='<?= $lfu['kodeHasilStatusFollowUp'] ?>'><?= $lfu['deskripsiHasilStatusFollowUp'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <script>
              $(document).ready(function() {
                //Cek kodeHasilStatusFollowUp
                kodeHasilStatusFollowUp = '<?= isset($list_follow_up[$fol_up_sekarang]) ? $list_follow_up[$fol_up_sekarang]['kodeHasilStatusFollowUp'] : '' ?>';
                if (kodeHasilStatusFollowUp == 4 || kodeHasilStatusFollowUp == 2) {
                  $('#input_kodeAlasanNotProspectNotDeal_<?= $fol_up_sekarang ?>').show();
                  $('#tglNextFollowUp_<?= $fol_up_sekarang ?>').removeAttr('required');
                  $('#tglNextFollowUpLabel_<?= $fol_up_sekarang ?>').html('');
                } else {
                  if (kodeHasilStatusFollowUp == 1) {
                    $('#tglNextFollowUp_<?= $fol_up_sekarang ?>').attr('required', 'required');
                    $('#tglNextFollowUpLabel_<?= $fol_up_sekarang ?>').html('*');
                  }
                }
              })

              $("#kodeHasilStatusFollowUp_<?= $fol_up_sekarang ?>").on("change", function(e) {
                data = $("#kodeHasilStatusFollowUp_<?= $fol_up_sekarang ?>").select2('data')[0];
                $('#tglNextFollowUp_<?= $fol_up_sekarang ?>').removeAttr('required');
                if (data != undefined) {
                  if (data.id == 2 || data.id == 4) {
                    $('#input_kodeAlasanNotProspectNotDeal_<?= $fol_up_sekarang ?>').show();
                  } else {
                    if (data.id == 1) {
                      $('#tglNextFollowUp_<?= $fol_up_sekarang ?>').attr('required', 'required');
                      $('#tglNextFollowUpLabel_<?= $fol_up_sekarang ?>').html('*');
                    }
                    $('#input_kodeAlasanNotProspectNotDeal_<?= $fol_up_sekarang ?>').hide();
                    $('#kodeAlasanNotProspectNotDeal_<?= $fol_up_sekarang ?>').val(null).trigger('change');
                  }
                }
              });
            </script>

            <div class="form-group" id="input_kodeAlasanNotProspectNotDeal_<?= $fol_up_sekarang ?>">
              <label class="col-sm-4 control-label">Alasan Follow Up <?= $fol_up_sekarang ?> Not Prospect/Not Deal *</label>
              <div class="form-input">
                <div class="col-sm-8">
                  <select style="width:100%" id="kodeAlasanNotProspectNotDeal_<?= $fol_up_sekarang ?>" class='form-control' name='kodeAlasanNotProspectNotDeal_<?= $fol_up_sekarang ?>' <?= $disabled ?> required>
                    <?php if (isset($list_follow_up[$fol_up_sekarang])) {
                      $lfu = $list_follow_up[$fol_up_sekarang]; ?>
                      <option value='<?= $lfu['kodeAlasanNotProspectNotDeal'] ?>'><?= $lfu['alasanNotProspectNotDeal'] ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <script>
              $(document).ready(function() {
                //Cek kodeAlasanNotProspectNotDeal
                kodeAlasanNotProspectNotDeal = '<?= isset($list_follow_up[$fol_up_sekarang]) ? $list_follow_up[$fol_up_sekarang]['kodeAlasanNotProspectNotDeal'] : '' ?>';
                if (kodeAlasanNotProspectNotDeal == 5) {
                  $('#input_keteranganAlasanLainnya_<?= $fol_up_sekarang ?>').show();
                }
              })

              $("#kodeAlasanNotProspectNotDeal_<?= $fol_up_sekarang ?>").on("change", function(e) {
                data = $("#kodeAlasanNotProspectNotDeal_<?= $fol_up_sekarang ?>").select2('data')[0];
                if (data == undefined) {
                  $('#input_keteranganAlasanLainnya_<?= $fol_up_sekarang ?>').hide();
                  $('#keteranganAlasanLainnya_<?= $fol_up_sekarang ?>').val('');
                } else {
                  if (data.id == 5) {
                    $('#input_keteranganAlasanLainnya_<?= $fol_up_sekarang ?>').show();
                  } else {
                    $('#input_keteranganAlasanLainnya_<?= $fol_up_sekarang ?>').hide();
                    $('#keteranganAlasanLainnya_<?= $fol_up_sekarang ?>').val('');
                  }
                }
              });
            </script>
            <div class="form-group" id="input_keteranganAlasanLainnya_<?= $fol_up_sekarang ?>">
              <label class="col-sm-4 control-label">Keterangan Alasan Lainnya *</label>
              <div class="form-input">
                <div class="col-sm-8">
                  <input type="text" class="form-control" id='keteranganAlasanLainnya_<?= $fol_up_sekarang ?>' name='keteranganAlasanLainnya_<?= $fol_up_sekarang ?>' required value='<?= isset($list_follow_up[$fol_up_sekarang]) ? $list_follow_up[$fol_up_sekarang]['keteranganAlasanLainnya'] : '' ?>' <?= $disabled ?>>
                </div>
              </div>
            </div>
          </div>
        <?php $fol_up_sekarang++;
        } ?>
      </div>
      <div class="form-group" style='padding-top:20px'>
        <div class="col-sm-6">
          <button type="button" id="backTo_<?= $back ?>" class="btn btn-primary btn-flat" onclick="saveDataFollowUp(this,'<?= $back ?>','back',<?= $i ?>,false)"><i class="fa fa-backward"></i> Halaman Sebelumnya</button>
        </div>
        <div class="col-sm-6" align="right">
          <?php if ($i < $tot_tab_fol) { ?>
            <button onclick="saveDataFollowUp(this,'data_follow_up_<?= $i + 1 ?>','next',<?= $i ?>,false)" type="button" id="#backTo_data_pendukung_probing_1" class="btn btn-primary btn-flat"><i class="fa fa-forward"></i> Halaman Berikutnya</button>
          <?php } else {
            $set_end = true; ?>
            <?php if ($disabled == '') { ?>
              <?php if ($hide_tambah==false) { ?>
                <button onclick="tambahDataFollowUp(this,<?= count($list_follow_up) + 1 ?>,<?= $i ?>)" type="button" id="#nextTo_data_follow_up_<?= $i + 1 ?>" class="btn btn-info btn-flat">Tambah Follow Up <?= count($list_follow_up) + 1 ?></button>
              <?php }?>
              <button onclick="saveDataFollowUp(this,'data_follow_up_<?= $i ?>',1,<?= $i ?>)" type="button" class="btn bg-blue btn-flat">Simpan Follow Up</button>
            <?php } ?>
          <?php } ?>
        </div>
        <?php if (isset($set_end)) { ?>
          <div class="col-sm-12" align='left' style='margin-top:20px;padding-top:15px;border-top:1px solid #f4f4f4'>
            <?php if ($hide_tambah==false){ ?>
            *) Tombol <button type="button" class="btn btn-info btn-flat btn-xs">
  Tambah Follow Up <?= count($list_follow_up) + 1 ?></button> digunakan untuk menambah form follow up baru.
            <br>
            <?php } ?>
            *) Tombol <button type="button" class="btn bg-blue btn-flat btn-xs">Simpan Follow Up</button> digunakan untuk menyimpan data follow up.
          </div>
        <?php } ?>
      </div>
    </form>
  </div>
<?php } ?>
<?php
$data['data'] = ['selectMediaKomunikasiFolupMulti', 'selectStatusKomunikasiFolUpMulti', 'selectKategoriStatusKomunikasiMulti', 'selectHasilStatusFollowUpMulti', 'selectAlasanNotProspectNotDealMulti'];
$data['total_fol_up'] = $total_fol_up;
$this->load->view('additionals/dropdown_search_menu_leads_customer_data', $data); ?>
<?php $this->load->view('manage_customer/leads_customer_data/modal_follow_up'); ?>
<script>
  var set_fu = 0;
  var set_tabs = '';
  var position = '';
  var default_name_button = '';

  function saveDataFollowUp(el, tabs, posit, fu, is_save = true) {
    set_fu = fu;
    set_tabs = tabs;
    position = posit;
    if (position == 'back') {
      default_name_button = '<i class = "fa fa-backward"></i> Halaman Sebelumnya';
      changeTabs(tabs);
      return false;
    } else if (position == 'next') {
      default_name_button = '<i class = "fa fa-forward"></i> Halaman Berikutnya';
      changeTabs(tabs);
    } else {
      default_name_button = 'Simpan Follow Up';
    }

    <?php if ($disabled == 'disabled') { ?>
      changeTabs(tabs);
      return false;
    <?php } ?>
    $('#form_data_follow_up_' + fu).validate({
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
    if ($('#form_data_follow_up_' + fu).valid()) // check if form is valid
    {
      if (is_save == true) {
        showModalFollowUp();
      }
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

  function tambahDataFollowUp(el, fu, tabs_no) {
    Swal.fire({
      title: 'Apakah Anda Yakin Menambah Follow Up Baru ?',
      showCancelButton: true,
      confirmButtonText: 'Ya',
      cancelButtonText: 'Batal',
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        values = {
          fol: fu,
          leads_id: "<?= $row->leads_id ?>",
          tabs: 'data_follow_up_' + tabs_no
        }
        $.ajax({
          beforeSend: function() {
            $(el).html('<i class="fa fa-spinner fa-spin"></i> Process');
            $(el).attr('disabled', true);
          },
          enctype: 'multipart/form-data',
          url: '<?= site_url(get_controller() . '/tambahDataFollowUp') ?>',
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
            $(el).html('Tambah Folllow Up ' + fu);
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
            $(el).html('Tambah Folllow Up ' + fu);
            $(el).attr('disabled', false);
          }
        });
      } else if (result.isDenied) {
        // Swal.fire('Changes are not saved', '', 'info')
      }
    })
  }

  function saveDataFollowUpFinal(el,tabs_no) {
    Swal.fire({
      title: 'Apakah Anda Yakin ?',
      showCancelButton: true,
      confirmButtonText: 'Simpan',
      cancelButtonText: 'Batal',
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        var val_form_follow_up = new FormData($('#form_data_follow_up_' + set_fu)[0]);
        val_form_follow_up.append('leads_id', '<?= $row->leads_id ?>');
        val_form_follow_up.append('statusProspek', statusProspek);
        val_form_follow_up.append('set_tabs', set_tabs);
        $.ajax({
          beforeSend: function() {
            $(el).html('<i class="fa fa-spinner fa-spin"></i> Process');
            $(el).attr('disabled', true);
          },
          enctype: 'multipart/form-data',
          url: '<?= site_url(get_controller() . '/saveEditFollowUp') ?>',
          type: "POST",
          data: val_form_follow_up,
          processData: false,
          contentType: false,
          // cache: false,
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
            }
            $(el).attr('disabled', false);
            $(el).html(default_name_button);
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
            $(el).html(default_name_button);
            $(el).attr('disabled', false);
          }
        });
      } else if (result.isDenied) {
        // Swal.fire('Changes are not saved', '', 'info')
      }
    })
  }

  function showModalFollowUp() {
    $('#mdl_pic_md').text(': ' + $('#pic_<?= $total_fol_up ?> option:selected').text());
    $('#mdl_tglFollowUp').text(': ' + $('#tglFollowUp_<?= $total_fol_up ?>').val());
    $('#mdl_keteranganFollowUp').text(': ' + $('#keteranganFollowUp_<?= $total_fol_up ?>').val());
    $('#mdl_id_media_kontak_fu').text(': ' + $('#id_media_kontak_fu_<?= $total_fol_up ?>').select2('data')[0].text);
    $('#mdl_tglNextFollowUp').text(': ' + $('#tglNextFollowUp_<?= $total_fol_up ?>').val());
    $('#mdl_statusProspek').text(': ' + $('#statusProspek_<?= $total_fol_up ?>').val());
    $('#mdl_keteranganNextFollowUp').text(': ' + $('#keteranganNextFollowUp_<?= $total_fol_up ?>').val());
    $('#mdl_id_status_fu').text(': ' + $('#id_status_fu_<?= $total_fol_up ?>').select2('data')[0].text);
    $('#mdl_kategori_status_komunikasi').text(': ' + $('#kategori_status_komunikasi_<?= $total_fol_up ?>').val());
    $('#mdl_kodeHasilStatusFollowUp').text(': ' + $('#kodeHasilStatusFollowUp_<?= $total_fol_up ?>').select2('data')[0].text);
    $('#mdl_kodeAlasanNotProspectNotDeal').text(': ' + $('#kodeAlasanNotProspectNotDeal_<?= $total_fol_up ?>').select2('data')[0].text);
    $('#mdl_keteranganAlasanLainnya').text(': ' + $('#keteranganAlasanLainnya_<?= $total_fol_up ?>').val());
    $('#modalFollowUp').modal('show');
  }
</script>