<?php
date_default_timezone_set('Asia/Jakarta');

function getTommorowStartOperation($today, $operatingHour)
{
  $tommorow = clone $today;
  # add 1 day for tommorow
  $tommorow->modify('+1 days');

  # add 1 day when sunday
  $tommorow = checkSundayHoliday($tommorow);

  $tommorowDate = $tommorow->format('Y-m-d');
  if (strtolower($tommorow->format('D')) == 'sat') {
    $tommorowStartOperation = DateTime::createFromFormat('Y-m-d H:i', $tommorowDate . ' ' . $operatingHour['weekend']['start']);
  } else {
    $tommorowStartOperation = DateTime::createFromFormat('Y-m-d H:i', $tommorowDate . ' ' . $operatingHour['weekday']['start']);
  }

  return $tommorowStartOperation;
}

function checkSundayHoliday($today)
{
  # add 1 day when sunday
  $today = clone $today;
  if (strtolower($today->format('D')) == 'sun') {
    $today->modify("+1 day");
  } else {
    #for holiday, || SELECT COUNT(*) FROM ms_holiday WHERE holiday_date = '{$checkDate}'
    $CI = &get_instance();
    $checkDate = $today->format('Y-m-d');
    $libur = $CI->db->query("SELECT COUNT(tgl_libur) c
      from ms_holiday 
      WHERE tgl_libur='$checkDate'
    ")->row()->c;
    if ($libur > 0) {
      $today->modify("+1 day");
    }
  }

  return $today;
}

function calculateDeadline($actionTimeStr, $SLAStr, $operatingHour)
{
  /*
			+ Accept SLA Interval : Minute, Hour or Day
			+ Doesn't calculate holiday
			+ Weekend operation only for saturday, sunday always off
			+ If SLA Interval is in "Day" it doesn't use operating hour in calculation
		*/

  $SLAStr = strtolower($SLAStr);
  # Validate allowed interval measurement
  if (strpos($SLAStr, 'minute') === FALSE && strpos($SLAStr, 'hour') === FALSE && strpos($SLAStr, 'day') === FALSE) {
    return false;
  }

  # Get today [sun, mon, tue, etc..] and date type [weekday/weekend]
  $action = new DateTime($actionTimeStr);
  $deadline = clone $action;

  # If SLA Interval is in "Day" it doesn't use operating hour in calculation
  if (strpos($SLAStr, 'day') !== false) {
    $dayCount = intval($SLAStr);

    # limit day interval 1 - 30 (inclusive)
    if ($dayCount < 1 || $dayCount > 30) {
      return false;
    }

    while ($dayCount > 0) {
      $deadline->modify("+1 day");
      $dayCount = $dayCount - 1;

      # add 1 day when sunday
      $deadline = checkSundayHoliday($deadline);

      # adjust to operating hour (if needed)
      $todayDate = $deadline->format('Y-m-d');
      $today = strtolower($deadline->format('D'));

      $dayType = 'weekday';
      if ($today == 'sun' || $today == 'sat') {
        $dayType = 'weekend';
      }

      # Get Today Start and End Operating Time
      $startOperation = DateTime::createFromFormat('Y-m-d H:i', $todayDate . ' ' . $operatingHour[$dayType]['start']);
      $endOperation = DateTime::createFromFormat('Y-m-d H:i', $todayDate . ' ' . $operatingHour[$dayType]['end']);

      if ($deadline < $startOperation) {
        # Sebelum jam operasional
        $deadline = clone $startOperation;
      } else if ($deadline > $endOperation) {
        # Setelah jam operasional
        $deadline = clone $endOperation;
      }
    }

    return $deadline;
  }

  # If SLA Interval is in "Hour" or "Minute"
  if (strpos($SLAStr, 'hour') !== false) {
    # hour to second
    $remainingSeconds = intval($SLAStr) * 3600;
  } else {
    # minute to second
    $remainingSeconds = intval($SLAStr) * 60;
  }

  while ($remainingSeconds > 0) {
    $todayDate = $deadline->format('Y-m-d');
    $today = strtolower($deadline->format('D'));

    $dayType = 'weekday';
    if ($today == 'sun' || $today == 'sat') {
      $dayType = 'weekend';
    }

    # Get Today Start and End Operating Time
    $startOperation = DateTime::createFromFormat('Y-m-d H:i', $todayDate . ' ' . $operatingHour[$dayType]['start']);
    $endOperation = DateTime::createFromFormat('Y-m-d H:i', $todayDate . ' ' . $operatingHour[$dayType]['end']);

    /*
				Kondisi waktu yang mungkin terjadi :
				1. Di dalam jam operasional
				2. Sebelum jam operasional
				3. Setelah jam operasional
				
				Ada Kemungkinan ketika waktu + SLA Melewati Jam Operasional
			*/

    if ($deadline < $startOperation) {
      # case 2. Sebelum jam operasional
      $deadline = clone $startOperation;
    }

    if ($deadline >= $startOperation && $deadline < $endOperation) {
      # case 1. Di dalam jam operasional
      $addSecondsToday = abs($endOperation->getTimestamp() - $deadline->getTimestamp()); # hitung selisih detik : jam tutup operasional - jam aksi

      if (($remainingSeconds - $addSecondsToday) > 0) {
        # masih ada sisa (lebih dari jam operasional)
        $deadline->modify('+' . $addSecondsToday . ' seconds');
        $remainingSeconds = $remainingSeconds - $addSecondsToday;
      } else {
        # sudah sisa beberapa detik
        $deadline->modify('+' . $remainingSeconds . ' seconds');
        $remainingSeconds = 0;
      }
    } else {
      # case 3. Setelah jam operasional
      # Get tommorow start operation
      $deadline = getTommorowStartOperation($deadline, $operatingHour);
    }

    # add 1 day when sunday
    $deadline = checkSundayHoliday($deadline);
  }

  return $deadline;
}

function operatingHour($kode_dealer = null)
{
  $CI = &get_instance();
  if ($kode_dealer == null) {
    $where = "WHERE kode_dealer IS NULL";
  } else {
    $where = "WHERE kode_dealer='$kode_dealer'";
  }
  $jam_op = $CI->db->query("SELECT LEFT(jam_mulai_weekday,5) jam_mulai_weekday,LEFT(jam_selesai_weekday,5) jam_selesai_weekday, LEFT(jam_mulai_weekend,5)jam_mulai_weekend, LEFT(jam_selesai_weekend,5) jam_selesai_weekend
      from ms_jam_operasional $where
    ")->row();
  if ($jam_op != null) {
    return [
      'weekday' => ['start' => $jam_op->jam_mulai_weekday, 'end' => $jam_op->jam_selesai_weekday],
      'weekend' => ['start' => $jam_op->jam_mulai_weekend, 'end' => $jam_op->jam_selesai_weekend],
    ];
  }
}
