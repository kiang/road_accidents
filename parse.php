<?php

$result = array();

foreach(glob(__DIR__ . '/*.csv') AS $csvFile) {
  $fh = fopen($csvFile, 'r');
  fgetcsv($fh, 2048);
  fgetcsv($fh, 2048);
  fgetcsv($fh, 2048);
  while($line = fgetcsv($fh, 2048)) {
    $parts = preg_split('/[^0-9]+/', $line[0]);
    $parts[0] += 1911;
    if(!isset($parts[2])) {
      $year = '-';
    } else {
      $year = $parts[0];
    }

    $pos1 = strpos($line[1], '巷');
    $pos2 = strpos($line[1], '街');
    $pos3 = strpos($line[1], '段');
    $pos4 = strpos($line[1], '路');
    $pos5 = strpos($line[1], '道');
    $key = false;
    if(false !== $pos1) {
      $key = substr($line[1], 0, $pos1+3);
    } elseif(false !== $pos2) {
      $key = substr($line[1], 0, $pos2+3);
    } elseif(false !== $pos3) {
      $key = substr($line[1], 0, $pos3+3);
    } elseif(false !== $pos4) {
      $key = substr($line[1], 0, $pos4+3);
    } elseif(false !== $pos5) {
      $key = substr($line[1], 0, $pos5+3);
    }

    if(false !== $key) {
      if(!isset($result[$key])) {
        $result[$key] = array();
      }
      if(!isset($result[$key][$year])) {
        $result[$key][$year] = array();
      }
      if(!isset($result[$key][$year]['事故'])) {
        $result[$key][$year]['事故'] = 0;
      }
      ++$result[$key][$year]['事故'];

      $parts = explode(';', $line[2]);
      foreach($parts AS $part) {
        preg_match('/[0-9]+/', $part, $n);
        if(isset($n[0])) {
          $n = $n[0];
          $k = substr($part, 0, strpos($part, $n));
          if(!isset($result[$key][$year][$k])) {
            $result[$key][$year][$k] = 0;
          }
          $result[$key][$year][$k] += $n;
        }
      }
    }
  }
}

$fh = array();
foreach($result AS $key => $val1) {
  $city = mb_substr($key, 0, 3, 'utf-8');
  $key = mb_substr($key, 3, null, 'utf-8');
  if(!isset($fh[$city])) {
    $fh[$city] = fopen(__DIR__ . '/result/' . $city. '.csv', 'w');
  }
  foreach($val1 AS $year => $val2) {
    fputcsv($fh[$city], array_merge(array($key, $year), $val2));
  }
}
