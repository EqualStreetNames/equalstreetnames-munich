<?php
function csv_to_json($filename) {
  $handle = fopen($filename,"r");
  $keys=fgetcsv($handle);
  $data=fgetcsv($handle);
  $data_array=array();
  while ($data!==false) {
    $tmp=array();
    foreach ($data as $key=>$value) {
      $tmp[$keys[$key]]=$value;
    }
    array_push($data_array,$tmp);
    $data=fgetcsv($handle);
  }                                                          
  fclose($handle);
  return json_encode($data_array,JSON_INVALID_UTF8_SUBSTITUTE);
}
?>