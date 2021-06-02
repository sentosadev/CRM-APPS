<?php if (isset($init)) { ?>
  <style>
    /*!
 * Bootstrap Steps v1.0.3 (https://github.com/ycs77/bootstrap-steps)
 * Copyright 2020 Lucas Yang <yangchenshin77@gmail.com>
 * Licensed under MIT (https://github.com/ycs77/bootstrap-steps/blob/master/LICENSE)
 */
    .steps {
      padding: 0;
      padding-bottom: 40px;
      margin: 0;
      list-style: none;
      display: flex;
      overflow-x: auto;
      width: 100%
    }

    .steps .step:first-child {
      margin-left: auto
    }

    .steps .step:last-child {
      margin-right: auto
    }

    .step:first-of-type .step-circle::before {
      display: none
    }

    .step:last-of-type .step-content {
      padding-right: 0
    }

    .step-content {
      box-sizing: content-box;
      display: flex;
      align-items: center;
      flex-direction: column;
      width: 34rem;
      min-width: 34rem;
      max-width: 34rem;
      padding-top: .5rem;
      padding-right: 1rem
    }

    .step-circle {
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
      width: 4rem;
      height: 4rem;
      color: #fff;
      border: 2px solid #cccccc;
      border-radius: 100%;
      background-color: #cccccc;
      font-weight: 600;

    }

    .step-circle::before {
      content: "";
      display: block;
      position: absolute;
      top: 50%;
      left: -2px;
      width: calc(34rem + 1rem - 4rem);
      height: 2px;
      transform: translate(-100%, -50%);
      color: #cccccc;
      background-color: currentColor
    }

    .step-text {
      color: #cccccc;
      word-break: break-all;
      margin-top: .25em;
      font-weight: 600;
    }

    .step-active .step-circle {
      color: #fff;
      background-color: #007bff;
      border-color: #007bff
    }

    .step-active .step-circle::before {
      color: #007bff
    }

    .step-active .step-text {
      color: #007bff
    }

    .step-error .step-circle {
      color: #fff;
      background-color: #dc3545;
      border-color: #dc3545
    }

    .step-error .step-circle::before {
      color: #dc3545
    }

    .step-error .step-text {
      color: #dc3545
    }

    .step-success .step-circle {
      color: #28a745;
      background-color: #fff;
      border-color: #28a745
    }

    .step-success .step-circle::before {
      color: #28a745
    }

    .step-success .step-text {
      color: #28a745
    }
  </style>
<?php } ?>
<ul class="steps">
  <li class="step <?= in_array(1, $set_active) ? 'step-active' : '' ?>">
    <div class="step-content">
      <span class="step-circle">1</span>
      <span class="step-text">Data Registrasi</span>
    </div>
  </li>
  <li class="step">
    <div class="step-content <?= in_array(2, $set_active) ? 'step-active' : '' ?>">
      <span class="step-circle">2</span>
      <span class="step-text">Pengajuan & Kontak Sales</span>
    </div>
  </li>
  <li class="step">
    <div class="step-content <?= in_array(3, $set_active) ? 'step-active' : '' ?>">
      <span class="step-circle">3</span>
      <span class="step-text">Data Pendukung & Probing</span>
    </div>
  </li>
</ul>