<div class="tab-pane" id="main_dealer_probing">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div id="carousel_fu_md" class="carousel slide" data-ride="carousel" style="background:#ececec;min-height: 367px;">
        <ol class="carousel-indicators">
          <li data-target="#carousel_fu_md" data-slide-to="0" class="active"></li>
          <li data-target="#carousel_fu_md" data-slide-to="1" class=""></li>
        </ol>
        <div class="carousel-inner">
          <div class="item active">
            <div class="row" style='padding-top:20px'>
              <div class="col-sm-offset-2 col-sm-8">
                <table class='table table-condensed' style="background:#fff">
                  <tr>
                    <td align="right" style='width:50%'>Tgl. Follow Up&nbsp;&nbsp;</td>
                    <td>: -</td>
                  </tr>
                  <tr>
                    <td align="right">Keterangan Follow Up&nbsp;&nbsp;</td>
                    <td>: -</td>
                  </tr>
                  <tr>
                    <td align="right">Media Follow Up&nbsp;&nbsp;</td>
                    <td>: -</td>
                  </tr>
                  <tr>
                    <td align="right">Tgl. Next Follow Up&nbsp;&nbsp;</td>
                    <td>: -</td>
                  </tr>
                  <tr>
                    <td align="right">Ket. Next Follow Up&nbsp;&nbsp;</td>
                    <td>: -</td>
                  </tr>
                  <tr>
                    <td align="right">Status Follow Up&nbsp;&nbsp;</td>
                    <td>: -</td>
                  </tr>
                  <tr>
                    <td align="right">Hasil Follow Up&nbsp;&nbsp;</td>
                    <td>: -</td>
                  </tr>
                  <tr>
                    <td align="right">Alasan FU Not Interest&nbsp;&nbsp;</td>
                    <td>: -</td>
                  </tr>
                  <tr>
                    <td align="right">Ket. Alasan Not Interest Lainnya&nbsp;&nbsp;</td>
                    <td>: -</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          <div class="item">
            xxxxxx
          </div>
        </div>
        <a class="left carousel-control" href="#carousel_fu_md" data-slide="prev">
          <span class="fa fa-angle-left"></span>
        </a>
        <a class="right carousel-control" href="#carousel_fu_md" data-slide="next">
          <span class="fa fa-angle-right"></span>
        </a>
      </div>
    </div>
  </div>
</div>

<script>
  function setCarouselFollowUpMD(params) {
    //Set Carousel FU MD
    html_indicators = '';
    html_content = '';
    i_i = 0;
    for (dt of params) {
      active = i_i == 0 ? 'active' : '';
      fol_sebelumnya = dt.followUpKe - 1;
      html_indicators += '<li data-target="#carousel_fu_md" data-slide-to="' + i_i + '" class="' + active + '"></li>';

      html_content += '<div class="item ' + active + '">';
      html_content += '<table class="table table-condensed" style="margin-top:20px;margin-left:20%;width:60%;background:#fff">';
      html_content += '<tr>';
      html_content += '<td align="right" style="width:50%">Tgl. Follow Up ' + string(dt.followUpKe) + '&nbsp;&nbsp;</td>';
      html_content += '<td>: ' + string(dt.tglFollowUp) + '</td>';
      html_content += '</tr>';
      html_content += '<tr>';
      html_content += '<td align="right">Keterangan Follow Up ' + string(dt.followUpKe) + '&nbsp;&nbsp;</td>';
      html_content += '<td>: ' + string(dt.keteranganFollowUp) + '</td>';
      html_content += '</tr>';
      html_content += '<tr>';
      html_content += '<td align="right">Media Follow Up ' + string(dt.followUpKe) + '&nbsp;&nbsp;</td>';
      html_content += '<td>: ' + string(dt.media_kontak_fu) + '</td>';
      html_content += '</tr>';
      html_content += '<tr>';
      html_content += '<td align="right">Tgl. Next Follow Up&nbsp;&nbsp;</td>';
      html_content += '<td>: ' + string(dt.tglNextFollowUp) + '</td>';
      html_content += '</tr>';
      html_content += '<tr>';
      html_content += '<td align="right">Ket. Next Follow Up&nbsp;&nbsp;</td>';
      html_content += '<td>: ' + string(dt.keteranganNextFollowUp) + '</td>';
      html_content += '</tr>';
      html_content += '<tr>';
      html_content += '<td align="right">Status Follow Up ' + string(dt.followUpKe) + '&nbsp;&nbsp;</td>';
      html_content += '<td>: ' + string(dt.status_fu) + '</td>';
      html_content += '</tr>';
      html_content += '<tr>';
      html_content += '<td align="right">Hasil Follow Up ' + string(dt.followUpKe) + '&nbsp;&nbsp;</td>';
      html_content += '<td>: ' + string(dt.deskripsiHasilStatusFollowUp) + '</td>';
      html_content += '</tr>';
      html_content += '<tr>';
      if (fol_sebelumnya > 0) {
        html_content += '<td align="right">Alasan FU  ' + fol_sebelumnya + ' Not Interest&nbsp;&nbsp;</td>';
        html_content += '<td>: ' + string(dt.alasanNotProspectNotDeal) + '</td>';
        html_content += '</tr>';
        html_content += '<tr>';
        html_content += '<td align="right">Ket. Alasan Not Interest Lainnya&nbsp;&nbsp;</td>';
        html_content += '<td>: ' + string(dt.keteranganLainnyaNotProspectNotDeal) + '</td>';
        html_content += '</tr>';
      }
      html_content += '</table>';
      html_content += '</div>';

      i_i++;
    }
    $('#carousel_fu_md .carousel-indicators').html(html_indicators);
    $('#carousel_fu_md .carousel-inner').html(html_content);
  }
</script>