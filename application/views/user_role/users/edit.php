  <?php $disabled = ''; ?>
  <section class="content">
    <!-- SELECT2 EXAMPLE -->
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="<?= site_url(get_slug()) ?>">
            <button class="btn bg-red btn-flat"><i class="fa fa-back"></i> Kembali</button>
          </a>
        </h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <form class="form-horizontal" id="form_">
          <div class="box-body">
            <div class="form-group">
              <label class="col-sm-2 control-label">ID User <span class='required'>*</span></label>
              <div class="form-input">
                <div class="col-sm-4">
                  <input type="text" class="form-control" disabled value='<?= $row->id_user ?>'>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">User Groups <span class='required'>*</span></label>
              <div class="form-input">
                <div class="col-sm-4">
                  <select class="form-control select2" style="width: 100%;" name='id_group' <?= $disabled ?>>
                    <option value=''>- Pilih -</option>
                    <?php foreach ($user_groups as $ug) { ?>
                      <option value='<?= $ug->id_group ?>' <?= $ug->id_group == $row->id_group ? 'selected' : '' ?>><?= $ug->nama_group ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Main Dealer / Dealer <span class='required'>*</span></label>
              <div class="form-input">
                <div class="col-sm-4">
                  <select class="form-control" style="width: 100%;" name='main_dealer_or_dealer' id="main_dealer_or_dealer" onchange="cekMainDealerOrDealer()" <?= $disabled ?>>
                    <option value=''>- Pilih -</option>
                    <option value='md' <?= $row->main_dealer_or_dealer == 'md' ? 'selected' : '' ?>>Main Dealer</option>
                    <option value='d' <?= $row->main_dealer_or_dealer == 'd' ? 'selected' : '' ?>>Dealer</option>
                  </select>
                </div>
              </div>
              <label class="col-sm-2 control-label" id="label_kode_dealer">Dealer <span class='required'>*</span></label>
              <div class="form-input" id="input_kode_dealer">
                <div class="col-sm-4">
                  <select style='width:100%' id="kode_dealer" class='form-control' name='kode_dealer' <?= $disabled ?>>
                    <option value='<?= $row->kode_dealer ?>'><?= (string)$row->kode_dealer == '' ? '- Pilih -' : $row->nama_dealer ?></option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Username <span class='required'>*</span></label>
              <div class="form-input">
                <div class="col-sm-4">
                  <input type="text" class="form-control" name='username' required value='<?= $row->username ?>' <?= $disabled ?> id="username">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Email <span class='required'>*</span></label>
              <div class="form-input">
                <div class="col-sm-4">
                  <input type="email" class="form-control" name='email' required value='<?= $row->email ?>' <?= $disabled ?>>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Password <span class='required'>*</span></label>
              <div class="form-input">
                <div class="col-sm-4">
                  <input type="password" class="form-control" name='password' <?= $disabled ?> id="password">
                  <span class="text-danger" style="font-style:italic">Kosongkan jika tidak ingin mengubah password</span>
                </div>
              </div>
              <div class="col-sm-4">
                <button class="btn btn-default bg-maroon btn-flat" type="button" onclick="generated()"><i class="fa fa-cogs"></i> Generate</button>
                <button class="btn btn-success btn-flat" type="button" onclick="showing()" id="btnShow"><i class="fa fa-eye"></i> Show Password</button>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Nama Lengkap <span class='required'>*</span></label>
              <div class="form-input">
                <div class="col-sm-4">
                  <input type="text" class="form-control" name='nama_lengkap' required value='<?= $row->nama_lengkap ?>' <?= $disabled ?>>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">No. HP <span class='required'>*</span></label>
              <div class="form-input">
                <div class="col-sm-4">
                  <input type="text" class="form-control" name='no_hp' required value='<?= $row->no_hp ?>' <?= $disabled ?> onkeypress="only_number(event)">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Images</label>
              <div class="col-sm-4">
                <input type="file" class="form-control" name='images' <?= $disabled ?>>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Aktif</label>
              <div class="col-sm-4">
                <input type="checkbox" class="flat-red" name="aktif" <?= $row->aktif == 1 ? 'checked' : '' ?> <?= $disabled ?>>
              </div>
            </div>
            <!-- <div class="form-group">
              <label class="col-sm-2 control-label">Password</label>
              <div class="col-sm-10">
                <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
              </div>
            </div> -->
          </div>
        </form>
      </div>
      <!-- /.box-body -->
      <?php if ($disabled == '') { ?>
        <div class="box-footer">
          <div class="form-group">
            <div class="col-sm-12" align="center">
              <button type="button" id="submitButton" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
    <!-- /.box -->
  </section>
  <script>
    var username = document.getElementById("username").value;

    $('#submitButton').click(function() {
      // Swal.fire('Any fool can use a computer')
      $('#form_').validate({
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
      var values = new FormData($('#form_')[0]);
      values.append('id_user', <?= $row->id_user ?>);
      if ($('#form_').valid()) // check if form is valid
      {
        Swal.fire({
          title: 'Apakah Anda Yakin ?',
          showCancelButton: true,
          confirmButtonText: 'Simpan',
          cancelButtonText: 'Batal',
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            $.ajax({
              beforeSend: function() {
                $('#submitButton').html('<i class="fa fa-spinner fa-spin"></i> Process');
                $('#submitButton').attr('disabled', true);
              },
              enctype: 'multipart/form-data',
              url: '<?= site_url(get_controller() . '/saveEdit') ?>',
              type: "POST",
              data: values,
              processData: false,
              contentType: false,
              // cache: false,
              dataType: 'JSON',
              success: function(response) {
                if (response.status == 1) {
                  window.location = response.url;
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
                  $('#submitButton').attr('disabled', false);
                }
                $('#submitButton').html('<i class="fa fa-save"></i> Save All');
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
                $('#submitButton').html('<i class="fa fa-save"></i> Save All');
                $('#submitButton').attr('disabled', false);
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
    })

    function cekMainDealerOrDealer() {
      selected = $('#main_dealer_or_dealer').val();
      if (selected == 'd') {
        $('#label_kode_dealer').show();
        $('#input_kode_dealer').show();
      } else if (selected == 'md') {
        $('#label_kode_dealer').hide();
        $('#input_kode_dealer').hide();
        $('#kode_dealer').val('');
      } else {
        $('#label_kode_dealer').hide();
        $('#input_kode_dealer').hide();
        $('#kode_dealer').val('');
      }
    }

    $(document).ready(function() {
      cekMainDealerOrDealer()
    })

    function generate(Length) {
      var result = '';
      var besar = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      var kecil = 'abcdefghijklmnopqrstuvwxyz';
      var angka = '0123456789';
      var simbol = "[|\\/~^:,;?!&%$@*+]";
      var besarLength = besar.length;
      var kecilLength = kecil.length;
      var angkaLength = angka.length;
      var simbolLength = besar.length;
      for (var i = 0; i < 1; i++) {
        result += besar.charAt(Math.floor(Math.random() * besarLength));
      }
      for (var i = 0; i < 1; i++) {
        result += angka.charAt(Math.floor(Math.random() * angkaLength));
      }
      for (var i = 0; i < 1; i++) {
        result += kecil.charAt(Math.floor(Math.random() * kecilLength));
      }
      for (var i = 0; i < 1; i++) {
        result += simbol.charAt(Math.floor(Math.random() * simbolLength));
      }
      for (var i = 0; i < 1; i++) {
        result += besar.charAt(Math.floor(Math.random() * besarLength));
      }
      for (var i = 0; i < 1; i++) {
        result += kecil.charAt(Math.floor(Math.random() * kecilLength));
      }
      for (var i = 0; i < 1; i++) {
        result += angka.charAt(Math.floor(Math.random() * angkaLength));
      }
      for (var i = 0; i < 1; i++) {
        result += simbol.charAt(Math.floor(Math.random() * simbolLength));
      }
      return result;
    }

    function generated() {
      $('#password').val(generate(10));
      is_password_valid()
    }

    function is_password_valid() {
      var passLength = document.getElementById("password").value;
      var textValidation = document.getElementById("textValidation");
      if (document.getElementById('password').value.length < 8) {
        document.getElementById("submitButton").disabled = true;
        pesan = `Hai ${username}, <br>Password kamu belum memenuhi kriteria, silahkan klik tombol <b>Generate</b> kembali ya`;
        Swal.fire({
          icon: 'error',
          title: '<font color="white">Peringatan</font>',
          html: '<font color="white">' + pesan + '</font>',
          background: '#dd4b39',
          confirmButtonColor: '#cc3422',
          confirmButtonText: 'Tutup',
          iconColor: 'white'
        })
      } else if (document.getElementById('password').value.length >= 8) {
        document.getElementById("submitButton").disabled = false;
        Swal.fire({
          icon: 'success',
          text: `Hai ${username}, Password kamu memenuhi kriteria`,
          title: 'Informasi',
        })
      }
    }

    function handleKeyUp() {

      var validationText = "";
      var myInput = document.getElementById("password");
      var myUsername = document.getElementById("username");
      var myInputVal = document.getElementById("password").value.toUpperCase();
      var myUsernameVal = document.getElementById("username").value.toUpperCase();
      var textValidation = document.getElementById("textValidation");
      var username = document.getElementById("username").value;
      var isValid = false;

      //   validate password tidak boleh mengandung username !
      if (myInputVal.includes(myUsernameVal)) {
        pesan = `Hai ${username}, Password kamu tidak boleh mengandung kata yang sama dengan username`;
        Swal.fire({
          icon: 'error',
          title: '<font color="white">Peringatan</font>',
          html: '<font color="white">' + pesan + '</font>',
          background: '#dd4b39',
          confirmButtonColor: '#cc3422',
          confirmButtonText: 'Tutup',
          iconColor: 'white'
        })
        document.getElementById("submitButton").disabled = true;
      }


      // Validate lowercase letters
      var lowerCaseLetters = /[a-z]/g;
      if (myInput.value.match(lowerCaseLetters)) {

      } else {

        validationText += "Harus memiliki minimal 1 huruf kecil !<br>";
        textValidation.style.color = "#DD4B39";
        textValidation.innerHTML = validationText;
        document.getElementById("submitButton").disabled = true;

      }

      // Validate capital letters
      var upperCaseLetters = /[A-Z]/g;
      if (myInput.value.match(upperCaseLetters)) {

      } else {

        validationText += "Harus memiliki minimal 1 huruf besar !<br>";
        textValidation.style.color = "#DD4B39";
        textValidation.innerHTML = validationText;
        document.getElementById("submitButton").disabled = true;

      }

      // Validate numbers
      var numbers = /[0-9]/g;
      if (myInput.value.match(numbers)) {

      } else {

        validationText += "Harus memiliki minimal 1 angka !<br>";
        textValidation.style.color = "#DD4B39";
        textValidation.innerHTML = validationText;
        document.getElementById("submitButton").disabled = true;

      }

      // Validate symbols
      var simbol = "[|\\/~^:,;?!&%$@*+]";
      if (myInput.value.match(simbol)) {

      } else {

        validationText += "Harus memiliki minimal 1 simbol !<br>";
        textValidation.style.color = "#DD4B39";
        textValidation.innerHTML = validationText;
        document.getElementById("submitButton").disabled = true;

      }

      // Validate length
      if (myInput.value.length < 8) {

        validationText += "Jumlah karakter minimal 8 digit !<br>";
        textValidation.style.color = "#DD4B39";
        textValidation.innerHTML = validationText;
        document.getElementById("submitButton").disabled = true;

      } else if (myInput.value.length >= 8 && myInput.value.match(simbol) && myInput.value.match(numbers) && myInput.value.match(lowerCaseLetters) && myInput.value.match(upperCaseLetters)) {


        if (myInputVal.includes(myUsernameVal)) {

          pesan = `Hai ${username}, Password kamu tidak boleh mengandung kata yang sama dengan username`;
          Swal.fire({
            icon: 'error',
            title: '<font color="white">Peringatan</font>',
            html: '<font color="white">' + pesan + '</font>',
            background: '#dd4b39',
            confirmButtonColor: '#cc3422',
            confirmButtonText: 'Tutup',
            iconColor: 'white'
          })
          document.getElementById("submitButton").disabled = true;
        } else {
          validationText = `Hai ${username}, Password kamu memenuhi kriteria.`;
          textValidation.style.color = "green";
          textValidation.innerHTML = validationText;

        }

        isValid = true;
        console.log(isValid);
        if (isValid == true) {
          document.getElementById("submitButton").disabled = false;
        } else {
          document.getElementById("submitButton").disabled = true;
        }
      }

      if (myInput.value.length == 0) {

        document.getElementById("submitButton").disabled = false;
        validationText = "";
        textValidation.style.color = "#DD4B39";
        textValidation.innerHTML = validationText;
      }

    }

    function showing() {
      var x = document.getElementById("password");
      var z = document.getElementById("btnShow");
      var caption = "";
      if (x.type === "password") {
        x.type = "text";
        z.style.backgroundColor = "#222B34";
        caption = "<i class='fa fa-eye-slash'></i> Hide Password";
        z.innerHTML = caption;
      } else {
        x.type = "password";
        z.style.backgroundColor = "#008D4C";
        caption = "<i class='fa fa-eye'></i> Show Password";
        z.innerHTML = caption;
      }
    }
  </script>