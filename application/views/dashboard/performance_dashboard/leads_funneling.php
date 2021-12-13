<style>
  .margin-bottom-table {
    margin-bottom: 5px
  }

  .margin-left-table {
    margin-left: 5px
  }

  .set-space-3 {
    padding: 0px 2px;
    width: 33.33%
  }

  .set-space-2 {
    padding: 0px 2px;
    width: 50%
  }
</style>
<div class="row">
  <div class="col-md-12">
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">Leads Funneling</h3>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-default">
              <div class="box-body">
                MD : PT. Sinar Sentosa Primatama
              </div>
            </div>
          </div>
        </div>
        <?php
        $user = user();
        if($user->md_d=='d'){ ?>
        <div class="row">
          <div class="col-md-12">
            <div class="box box-default">
              <div class="box-body">
                Dealer : <?=$user->nama_dealer?>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
        <div class="row">
          <div class="col-md-4">
            <div class="box box-default">
              <div class="box-body">
                <table>
                  <?php if ($user->md_d!='d') { ?>
                    <tr>
                    <td colspan=3>
                      <table class='table table-bordered table-condensed margin-bottom-table'>
                        <tr>
                          <td colspan=2 align='center'>
                            <div style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="lf_tot_leads" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Customer yang hadir dalam VE, melakukan interaksi pada website VE (seluruh CMS Source, kecuali Non CMS), dan telah terverifikasi pada backlog Verification and Classified Customer Leads Data">&nbsp;</div>
                            Total Leads
                          </td>
                        </tr>
                        <tr>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600'>Invited</div>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_invited"></div>
                          </td>
                          <td align='center'>
                            <div style='font-weight:600'>Non Invited</div>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_non_invited"></div>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td style='width:30%;vertical-align:top' rowspan=2>
                      <table class='table table-bordered table-condensed margin-bottom-table' style='min-height:210px'>
                        <tr>
                          <td align='center' colspan=2 style='vertical-align:middle'>
                            <div style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="lf_contacted_leads" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Data Leads yang sudah diprobing, terhubung oleh PIC VE MD, dan siap didispatch ke Dealer."></div>
                            Contacted Leads
                          </td>
                        </tr>
                        <tr>
                          <td align='center' style='width:50%' style='vertical-align:middle'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_contacted_leads_invited"></div>
                          </td>
                          <td align='center' style='width:50%' style='vertical-align:middle'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_contacted_leads_non_invited"></div>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td style="width:35%">
                      <table class='table table-bordered table-condensed margin-bottom-table margin-left-table'>
                        <tr>
                          <td align='center' colspan=2>
                            <div style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="lf_workload_leads" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Seluruh data konsumen yang sudah diverifikasi (Filter Sistem by prioritas No Telp, kemudian ke Email) dan belum pernah difollow up oleh PIC di MD (faktor internal di MD, terkait dari work load Front desk/PIC CRM  ketidakcukupan waktu untuk follow up by call/visit, kekurangan human resource, dan lain-lain )"></div>
                            Workload
                          </td>
                        </tr>
                        <tr>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_workload_leads_invited"></div>
                          </td>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_workload_leads_non_invited"></div>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td style="width:35%">
                      <table class='table table-bordered table-condensed margin-bottom-table'>
                        <tr>
                          <td align='center' colspan=2>
                            <div style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="lf_unreachable_leads" data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Seluruh data konsumen yang sudah difollowup via Telepon oleh PIC di MD tetapi tidak diangkat. Metode FU (Jenis Interaction Channel): Telpon, SMS, Email, Social Media (Facebook, Instagram, Twitter, Telegram) (RO)"></div>
                            Unreachable
                          </td>
                        </tr>
                        <tr>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_unreachable_leads_invited"></div>
                          </td>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_unreachable_leads_non_invited"></div>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <table class='table table-bordered table-condensed margin-bottom-table margin-left-table'>
                        <tr>
                          <td align='center' colspan=2>
                            <div data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Seluruh data konsumen yang sudah difollow up, ada interaksi langsung (sudah tersambung), tetapi perlu difollow up di lain waktu (Next Appointment)" style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="lf_rejected_leads"></div>
                            Rejected
                          </td>
                        </tr>
                        <tr>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_rejected_leads_invited"></div>
                          </td>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_rejected_leads_non_invited"></div>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td>
                      <table class='table table-bordered table-condensed margin-bottom-table'>
                        <tr>
                          <td align='center' colspan=2>
                            <div data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Proses follow up yang dilakukan ke konsumen, namun terdapat ketidaksesuaian / ketidakvalidan data" style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="lf_failed_leads"></div>
                            Failed
                          </td>
                        </tr>
                        <tr>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_failed_leads_invited"></div>
                          </td>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_failed_leads_non_invited"></div>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <?php } ?>
                  <tr>
                    <td style='width:30%;vertical-align:top' rowspan=2>
                      <table class='table table-bordered table-condensed margin-bottom-table' style='min-height:210px'>
                        <tr>
                          <td align='center' colspan=2 style='vertical-align:middle'>
                            <div data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Data Prospects yang di follow up oleh PIC Salespeople Dealer" style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="lf_contacted_prospects"></div>
                            Contacted Prospects
                          </td>
                        </tr>
                        <tr>
                          <td align='center' style='width:50%' style='vertical-align:middle'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_contacted_prospects_invited"></div>
                          </td>
                          <td align='center' style='width:50%' style='vertical-align:middle'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_contacted_prospects_non_invited"></div>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td style="width:35%">
                      <table class='table table-bordered table-condensed margin-bottom-table margin-left-table'>
                        <tr>
                          <td align='center' colspan=2>
                            <div data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Seluruh data konsumen prospect yang belum pernah difollow up oleh PIC di Dealer setelah di dispatch dari MD  sesuai filter Header yang dipilih ((faktor internal di Dealer, terkait dari work load Front desk/PIC CRM  ketidakcukupan waktu untuk follow up by call/visit, kekurangan human resource, dan lain-lain)" style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="lf_workload_prospects"></div>
                            Workload
                          </td>
                        </tr>
                        <tr>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_workload_prospects_invited"></div>
                          </td>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_workload_prospects_non_invited">88</div>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td style="width:35%">
                      <table class='table table-bordered table-condensed margin-bottom-table'>
                        <tr>
                          <td align='center' colspan=2>
                            <div data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Seluruh data konsumen yang sudah difollowup via Telepon oleh PIC di D tetapi tidak diangkatdan tampil sesuai filter Header yang dipilih . Metode FU (Jenis Interaction Channel): Telpon, SMS, Email, Social Media (Facebook, Instagram, Twitter, Telegram) (RO)" style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="lf_unreachable_prospects"></div>
                            Unreachable
                          </td>
                        </tr>
                        <tr>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_unreachable_prospects_invited"></div>
                          </td>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_unreachable_prospects_non_invited"></div>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <table class='table table-bordered table-condensed margin-bottom-table margin-left-table'>
                        <tr>
                          <td align='center' colspan=2>
                            <div data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Seluruh data konsumen yang sudah difollow up, ada interaksi langsung (sudah tersambung), tetapi perlu difollow up di lain waktu (Next Appointment), tampil sesuai filter Header yang dipilih." style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="lf_rejected_prospects"></div>
                            Rejected
                          </td>
                        </tr>
                        <tr>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_rejected_prospects_invited"></div>
                          </td>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_rejected_prospects_non_invited"></div>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td>
                      <table class='table table-bordered table-condensed margin-bottom-table'>
                        <tr>
                          <td align='center' colspan=2>
                            <div data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Proses follow up yang dilakukan ke konsumen, namun terdapat ketidaksesuaian data, tampil sesuai filter Header yang dipilih." style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="lf_failed_prospects"></div>
                            Failed
                          </td>
                        </tr>
                        <tr>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_failed_prospects_invited"></div>
                          </td>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="lf_failed_prospects_non_invited">88</div>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>

              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="box box-default">
              <div class="box-body">
                <table style="width:100%">
                  <tr>
                    <td class='set-space-3'>
                      <table class='table table-bordered table-condensed margin-bottom-table' style="background:rgb(255,153,0)">
                        <tr>
                          <td align='center' colspan=2>
                            <div data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Data Leads yang setelah dilakukan probing tertarik melakukan pembelian dengan kriteria Status Prospect berdasarkan selisih tanggal Next Follow Up dan tanggal Follow Upnya: Hot < 2minggu)" style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="fl_hot"></div>
                            HOT
                          </td>
                        </tr>
                        <tr>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="fl_hot_invited"></div>
                          </td>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="fl_hot_non_invited"></div>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td class='set-space-3'>
                      <table class='table table-bordered table-condensed margin-bottom-table' style="background:rgb(241,134,179)">
                        <tr>
                          <td align='center' colspan=2>
                            <div data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Data Leads yang setelah dilakukan probing tertarik melakukan pembelian dengan kriteria Status Prospect berdasarkan selisih tanggal Next Follow Up dan tanggal Follow Upnya: Medium (2-4minggu)" style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="fl_med"></div>
                            MED
                          </td>
                        </tr>
                        <tr>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="fl_med_invited"></div>
                          </td>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="fl_med_non_invited"></div>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td class='set-space-3'>
                      <table class='table table-bordered table-condensed margin-bottom-table' style="background:rgb(0,168,221)">
                        <tr>
                          <td align='center' colspan=2>
                            <div data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Data Leads yang setelah dilakukan probing tertarik melakukan pembelian dengan kriteria Status Prospect berdasarkan selisih tanggal Next Follow Up dan tanggal Follow Upnya: Low (>1bulan)" style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="fl_low"></div>
                            LOW
                          </td>
                        </tr>
                        <tr>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="fl_low_invited"></div>
                          </td>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="fl_low_non_invited"></div>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td class='set-space-3'>
                      <table class='table table-bordered table-condensed margin-bottom-table' style="background:rgb(29,148,77)">
                        <tr>
                          <td align='center' colspan=2>
                            <div data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Prospect yang tertarik, siap untuk membeli unit Honda, dan sudah memiliki nomor SPK sesuai login Dealer dan filter Header yang dipilih Dealer" style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="fl_deal"></div>
                            DEAL
                          </td>
                        </tr>
                        <tr>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="fl_deal_invited"></div>
                          </td>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="fl_deal_non_invited"></div>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td class='set-space-3'>
                      <table class='table table-bordered table-condensed margin-bottom-table' style="background:rgb(250,255,0)">
                        <tr>
                          <td align='center' colspan=2>
                            <div data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Prospect yang tertarik dan sudah didispatch ke Dealer, namun belum siap untuk membeli unit Honda (workload, unreachable, rejected, dan failed) sesuai filter Header yang dipilih." style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="fl_need_fu"></div>
                            NEED FU
                          </td>
                        </tr>
                        <tr>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="fl_need_fu_invited"></div>
                          </td>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="fl_need_fu_non_invited"></div>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td class='set-space-3'>
                      <table class='table table-bordered table-condensed margin-bottom-table' style="background:rgb(155,152,158)">
                        <tr>
                          <td align='center' colspan=2>
                            <div data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Status prospect yang sebelumnya telah menjadi Hot Prospect namun membatalkan keinginan untuk membeli setelah sales people melakukan follow up sesuai filter Header yang dipilih." style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="fl_not_deal"></div>
                            NOT DEAL
                          </td>
                        </tr>
                        <tr>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="fl_not_deal_invited"></div>
                          </td>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="fl_not_deal_non_invited"></div>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
                <table style="width:100%">
                  <tr>
                    <td class='set-space-2'>
                      <table class='table table-bordered table-condensed margin-bottom-table'>
                        <tr>
                          <td align='center' colspan=2>
                            <div data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Prospect yang tertarik, siap untuk membeli unit Honda, dan sudah memiliki nomor SPK Sales sesuai filter Header yang dipilih" style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="fl_sales"></div>
                            Sales
                          </td>
                        </tr>
                        <tr>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="fl_sales_invited"></div>
                          </td>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="fl_sales_non_invited"></div>
                          </td>
                        </tr>
                      </table>
                    </td>
                    <td class='set-space-2'>
                      <table class='table table-bordered table-condensed margin-bottom-table'>
                        <tr>
                          <td align='center' colspan=2>
                            <div data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="Prospect yang tertarik, siap untuk membeli unit Honda, dan sudah memiliki nomor SPK Indent sesuai filter Header yang dipilih." style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="fl_indent"></div>
                            Indent
                          </td>
                        </tr>
                        <tr>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="fl_indent_invited"></div>
                          </td>
                          <td align='center' style='width:50%'>
                            <div style='font-weight:600' class="dt_leads_funneling" id="fl_indent_non_invited"></div>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="box box-default">
              <div class="box-body">
                <div style='font-size:18pt;font-weight:600;text-align:center;padding-top:10px'>Conversion Rate</div>
                <table class='table table-bordered table-condensed margin-bottom-table' style="background:rgb(133,194,255)">
                  <tr>
                    <td align='center'>
                      <div data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="% perbandingan Sub-Chart Sales dengan Sub-Chart Total Leads sesuai dengan filter header yang dipilih" style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="lf_conv_sales_all_leads"></div>
                      Sales Of All Leads
                    </td>
                    <td align='center'>
                      <div data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="% perbandingan Sub-Chart Sales dengan Sub-Chart Contacted Prospects  sesuai dengan filter header yang dipilih" style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="lf_conv_sales_of_contacted"></div>
                      Sales Of Contacted
                    </td>
                  </tr>
                  <tr>
                    <td align='center'>
                      <div data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="% perbandingan Sub-Chart Sales dengan Sub-Chart Total Leads (Invited) sesuai dengan filter header yang dipilih" style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="lf_conv_sales_invited"></div>
                      Sales Of Invited Leads
                    </td>
                    <td align='center'>
                      <div data-toggle="tooltip" data-placement="bottom" data-html="true" data-original-title="% perbandingan Sub-Chart Sales dengan Sub-Chart Total Leads (Non Invited) sesuai dengan filter header yang dipilih" style='font-size:22pt;font-weight:600' class="dt_leads_funneling" id="lf_conv_sales_non_invited"></div>
                      Sales Of Non Invited Leads
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  function loadLeadsFunneling() {
    $.ajax({
      beforeSend: function() {
        $('.dt_leads_funneling').html('<i class="fa fa-spinner fa-spin"></i>');
      },
      enctype: 'multipart/form-data',
      url: '<?= site_url(get_controller() . '/loadLeadsFunneling') ?>',
      type: "POST",
      data: filter_values,
      dataType: 'JSON',
      success: function(response) {
        if (response.status == 1) {
          data = response.data;
          $('.dt_leads_funneling').each(function() {
            id = this.id;
            $('#' + id).html(data[id]);
          });
        }
      },
      error: function() {
        Swal.fire({
          icon: 'error',
          title: '<font color="white">Peringatan</font>',
          html: '<font color="white">Telah terjadi kesalahan Saat Melakukan Load Leads Funneling !</font>',
          background: '#dd4b39',
          confirmButtonColor: '#cc3422',
          confirmButtonText: 'Tutup',
          iconColor: 'white'
        })
      }
    });
  }
</script>