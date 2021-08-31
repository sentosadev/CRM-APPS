<div class="modal fade" id="modalFollowUp">
  <div class="modal-dialog" style='width:50%'>
    <div class="modal-content">
      <div class="modal-header bg-red disabled color-palette">
        <button style='color:white' type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" align='center'>Data Follow Up</h4>
      </div>
      <div class="modal-body">
        <div class="box box-default">
          <div class="box-body">
            <table style='width:100%' class='table'>
              <tr>
                <td align='right' width="45%">PIC MD</td>
                <td id="mdl_pic_md"></td>
              </tr>
              <tr>
                <td align='right'>Tanggal Follow Up</td>
                <td id="mdl_tglFollowUp"></td>
              </tr>
              <tr>
                <td align='right'>Keterangan Follow Up</td>
                <td id="mdl_keteranganFollowUp"></td>
              </tr>
              <tr>
                <td align='right'>Media Komunikasi Fol. Up</td>
                <td id="mdl_id_media_kontak_fu"></td>
              </tr>
              <tr>
                <td align='right'>Tgl. Next Follow Up</td>
                <td id="mdl_tglNextFollowUp"></td>
              </tr>
              <tr>
                <td align='right'>Status Prospek</td>
                <td id="mdl_statusProspek"></td>
              </tr>
              <tr>
                <td align='right'>Keterangan Next Action Follow Up</td>
                <td id="mdl_keteranganNextFollowUp"></td>
              </tr>
              <tr>
                <td align='right'>Status Komunikasi Fol. Up</td>
                <td id="mdl_id_status_fu"></td>
              </tr>
              <tr>
                <td align='right'>Kategori Status Komunikasi</td>
                <td id="mdl_kategori_status_komunikasi"></td>
              </tr>
              <tr>
                <td align='right'>Hasil Status Follow Up</td>
                <td id="mdl_kodeHasilStatusFollowUp"></td>
              </tr>
              <tr>
                <td align='right'>Alasan Follow Up Not Prospect/Not Deal</td>
                <td id="mdl_kodeAlasanNotProspectNotDeal"></td>
              </tr>
              <tr>
                <td align='right'>Keterangan Alasan Lainnya</td>
                <td id="mdl_keteranganAlasanLainnya"></td>
              </tr>
            </table>
          </div>
        </div>
        <div class="callout callout-danger">
          <h4>Perhatian !</h4>
          <p>Data follow up yang akan disimpan tidak dapat dilakukan pengeditan kembali. Silahkan dipastikan untuk kebenaran data yang diisi.</p>
        </div>
      </div>
      <div class="box-footer" align='center'>
        <button class='btn btn-primary btn-flat' type="button" onclick="saveDataFollowUpFinal(this)">Simpan Follow Up</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>