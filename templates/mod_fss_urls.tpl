<?php
header("Content-type: application/csv");
header("Content-Disposition: attachment; filename=abschluss_statistik.csv");
header("Pragma: no-cache");
header("Expires: 0");
echo $this->liste;
?>