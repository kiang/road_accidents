<?php

foreach(glob(__DIR__ . '/*.txt') AS $jsonFile) {
  $fh = fopen(__DIR__ . '/106_A' . substr($jsonFile, -10, 1) . '.csv', 'w');
  $lines = json_decode('[' . file_get_contents($jsonFile) . ']', true);
  fputcsv($fh, array(key($lines[0]), array_pop($lines[0])));
  fputcsv($fh, array(key($lines[1]), array_pop($lines[1])));
  fputcsv($fh, array_keys($lines[2]));
  array_shift($lines);
  array_shift($lines);
  foreach($lines AS $line) {
    fputcsv($fh, $line);
  }
}
