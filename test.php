<?php
$waktu_awal        = strtotime("2022-10-30 10:40:00");
$waktu_akhir       = strtotime(date("Y-m-d H:i:s")); 
$diff              = $waktu_akhir - $waktu_awal;
$selisihmenit      = floor($diff / 60);

echo 'selisih menit : ' .$selisihmenit. '<br>';
?>