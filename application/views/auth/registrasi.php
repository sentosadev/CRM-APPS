<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  <title>LAB-USU</title>

  <meta name="description" content="LAB-USU">
  <meta name="author" content="pixelcave">
  <meta name="robots" content="noindex, nofollow">

  <!-- Open Graph Meta -->
  <meta property="og:title" content="LAB-USU">
  <meta property="og:site_name" content="OneUI">
  <meta property="og:description" content="LAB-USU">
  <meta property="og:type" content="website">
  <meta property="og:url" content="">
  <meta property="og:image" content="">

  <!-- Icons -->
  <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
  <link rel="shortcut icon" href="<?= base_url('assets/media/favicons/lab_vsmall.png') ?>">
  <link rel="icon" type="image/png" sizes="192x192" href="<?= base_url('assets/media/favicons/lab_vsmall.png') ?>">
  <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/media/favicons/lab_small.png') ?>">
  <!-- END Icons -->

  <!-- Stylesheets -->
  <!-- Fonts and OneUI framework -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">
  <link rel="stylesheet" id="css-main" href="<?= base_url('assets/css/oneui.min.css') ?>">
  <link rel="stylesheet" id="css-main" href="<?= base_url() ?>assets/js/plugins/iziToast/dist/css/iziToast.css">

  <script src="<?= base_url('assets/js/oneui.core.min.js') ?>"></script>
  <script src="<?= base_url() ?>assets/js/oneui.app.min.js"></script>


  <script src="<?= base_url() ?>assets/js/plugins/select2/js/select2.full.min.js"></script>
  <script src="<?= base_url() ?>assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>

  <!-- <script src="<?= base_url() ?>assets/js/my.js"></script> -->
  <script src="<?= base_url() ?>assets/js/plugins/vue/vue.min.js"></script>
  <script src="<?= base_url() ?>assets/js/plugins/vue/accounting.js"></script>
  <script src="<?= base_url() ?>assets/js/plugins/vue/vue-numeric.min.js"></script>
  <script src="<?= base_url() ?>assets/js/plugins/iziToast/dist/js/iziToast.js"></script>

  <script src="<?= base_url() ?>assets/js/my_custom.js"></script>
  <script src="<?= base_url() ?>assets/_es6/pages/op_auth_signup.js"></script>

  <script>
    Vue.use(VueNumeric.default);
    Vue.filter('toCurrency', function(value) {
      return accounting.formatMoney(value, "", 0, ".", ",");
      return value;
    });
  </script>

</head>

<body>
  <div id="page-container">
    <!-- Main Container -->
    <main id="main-container">
      <?php if (isset($flash['sukses_registrasi'])) { ?>
        <div class="hero-static d-flex align-items-center">
          <div class="w-100">
            <!-- Sign In Section -->
            <div class="content content-full bg-white">
              <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4 py-4">
                  <!-- Header -->
                  <div class="text-center">
                    <p class="mb-2">
                    <h2>Selamat</h2>
                    <h3>Anda Berhasil melakukan registrasi</h3>
                    <h3>Silahkan klik tombol di bawah ini untuk melanjutkan login</h3>
                    <a href='<?= site_url('auth/login') ?>' type="button" style='color:white' class="btn btn-block btn-danger">LOGIN</a>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php } else { ?>
        <!-- Page Content -->
        <div class="hero-static d-flex align-items-center">
          <div class="w-100">
            <!-- Sign In Section -->
            <div class="content content-full bg-white">
              <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4 py-4">
                  <!-- Header -->
                  <div class="text-center">
                    <p class="mb-2">
                      <img src='<?= base_url('assets/media/favicons/lab_small.png') ?>' />
                    </p>
                    <!-- <h1 class="h4 mb-1">
                      LAB-USU
                    </h1> -->
                    <h2 class="h6 font-w400 text-muted mb-3">
                      Selamat Datang, Silahkan Login.
                    </h2>
                  </div>
                  <form id="form_">
                    <div class="py-3">
                      <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control form-control-lg form-control-alt" name="username" autocomplete="off" required>
                      </div>
                      <div class="form-group">
                        <label>E-Mail</label>
                        <input type="email" class="form-control form-control-lg form-control-alt" name="email" autocomplete="off" required>
                      </div>
                      <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control form-control-lg form-control-alt" name="password" placeholder="Password" autocomplete="off" required>
                      </div>
                      <div class="form-group">
                        <div class="d-md-flex align-items-md-center justify-content-md-between">
                          <!-- <div class="custom-control custom-switch">
                                                      <input type="checkbox" class="custom-control-input" id="login-remember" name="login-remember">
                                                      <label class="custom-control-label font-w400" for="login-remember">Remember Me</label>
                                                  </div> -->
                          <!-- <div class="py-2">
                                                      <a class="font-size-sm" href="op_auth_reminder2.html">Lupa Password?</a>
                                                  </div> -->
                        </div>
                      </div>
                    </div>
                    <div class="form-group row justify-content-center mb-0">
                      <div class="col-md-12 col-sm-12">
                        <button type="button" id="submitBtn" @click.prevent="login" class="btn btn-block btn-primary"><i class="fa fa-fw fa-save mr-1"></i> REGISTRASI</button>
                      </div>
                    </div>
                  </form>
                  <!-- END Sign In Form -->
                </div>
              </div>
            </div>
            <!-- END Sign In Section -->

            <!-- Footer -->
            <div class="font-size-sm text-center text-muted py-3">
              <strong>LAB-USU</strong> &copy; <span data-toggle="year-copy"></span>
            </div>
            <!-- END Footer -->
          </div>
        </div>
        <!-- END Page Content -->
        <script src="<?= base_url('assets/js/oneui.app.min.js') ?>"></script>
        <script>
          var msg_wajib = {
            tipe: 'danger',
            judul: 'Peringatan',
            pesan: 'Silahkan lengkapi field yang wajib diisi !'
          }

          var msg_error = {
            tipe: 'danger',
            judul: 'Peringatan',
            pesan: 'Telah terjadi kesalahan !'
          }

          var form_ = new Vue({
            el: '#form_',
            data: {},
            methods: {
              login: function() {
                $('#form_').validate({})
                if ($('#form_').valid()) // check if form is valid
                {
                  var values = {};
                  var form = $('#form_').serializeArray();
                  for (field of form) {
                    values[field.name] = field.value;
                  }
                  $.ajax({
                    beforeSend: function() {
                      $('#submitBtn').attr('disabled', true);
                      $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i>');
                    },
                    url: '<?= site_url('auth/save_registrasi') ?>',
                    type: "POST",
                    data: values,
                    cache: false,
                    dataType: 'JSON',
                    success: function(response) {
                      console.log(response)
                      if (response.status == 'sukses') {
                        window.location = response.link;
                      } else {
                        toast(response.pesan)
                        $('#submitBtn').attr('disabled', false);
                      }
                      $('#submitBtn').html('<i class="fa fa-fw fa-sign-in-alt mr-1"></i> LOGIN');
                    },
                    error: function() {
                      toast(msg_error)
                      $('#submitBtn').html('<i class="fa fa-fw fa-sign-in-alt mr-1"></i> LOGIN');
                      $('#submitBtn').attr('disabled', false);
                    }
                  });
                } else {
                  toast(msg_wajib)
                }
              }
            }
          })
        </script><?php } ?>
    </main>
    <!-- END Main Container -->
  </div>
</body>

</html>