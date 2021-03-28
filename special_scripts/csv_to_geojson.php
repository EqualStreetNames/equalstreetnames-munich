<?php
class FormatError extends Exception {
  function errorMessage() {
    return $this->getMessage();
  }
}
function checkHeader(array $header, string $filename) {
  if (count($header)!= 4) {
    throw new FormatError("$filename: Expected a 4 value header line. Got ".count($header)." values.");
  }
  if ($header[0]!="id") {
    throw new FormatError("$filename: Expected id header at first position in header line. Got $header[0].");
  }
  if ($header[1]!="gender") {
    throw new FormatError("$filename: Expected gender header at second position in header line. Got $header[1].");
  }
  if ($header[2]!="description") {
    throw new FormatError("$filename: Expected description header at third position in header line. Got $header[2].");
  }
  if ($header[3]!="language") {
    throw new FormatError("$filename: Expected language header at fourth position in header line. Got $header[3].");
  }
  return $header;
}
function isDigit(String $digit) {
  if (strlen($digit)!=1) {
    return false;
  }
  $digitArray=array("0","1","2","3","4","5","6","7","8","9");
  $digitCheck=false;
  foreach ($digitArray as $checkDigit) {
    $digitCheck = $digitCheck || $digit===$checkDigit;
  }
  return $digitCheck;
}
function isNumber($number) {
  foreach(str_split($number,1) as $digit) {
    if (!isDigit($digit)) {
      return false;
    }
  }
  return true;
}
function checkData(array $data,string $warnStart) {
  if (count($data)!=4) {
    return "$warnStart as it contains ".count($data)." elements instead of 4.\n";
  }
  $warnMsg="";
  if (!isNumber($data[0])) {
    $warnMsg="$warnStart as the first position contains not a number: $data[0].\n";
  }
  if (strlen($data[1])!=1) {
    $warnMsg.="$warnStart as the second position contains a ".strlen($data[3]);
    $warnMsg.=" char long string ($data[1]) but expected a 1 character long language identifier.\n";
  }
  if (strlen($data[2]==0)) {
    $warnMsg.="$warnStart as the third position is empty";
  } 
  if (strlen($data[3])!=2) {
    $warnMsg.="$warnStart as the fourth position contains a ".strlen($data[3]);
    $warnMsg.=" char long string ($data[3]) but expected a 2 character long language identifier.\n";
  }
  return $warnMsg;
}
function csv_to_json(string $filename) {
  $handle = fopen($filename,"r");
  $keys=checkHeader(fgetcsv($handle),$filename);
  $data=fgetcsv($handle);
  $line=0;
  $data_array=array();
  while ($data!==false) { 
    $line+=1;
    $warnMsg=checkData($data,"$filename: Skipped line $line");
    if ($warnMsg!="") {
      echo $warnMsg;
      $data=fgetcsv($handle);
      continue;
    }
    $id=$data[0];
    $gender=$data[1];
    $desc=$data[2];
    $lang=$data[3];
    if (!array_key_exists($id,$data_array)) {
      $tmp=array("gender"=>$gender);
      $tmp["descriptions"]=array($lang=>array("language"=>$lang, "value"=>$desc));
      $data_array[$id]=$tmp;
      $data=fgetcsv($handle);
      continue;
    }
    if ($data_array[$id]["gender"]!==$gender) {
      echo "$filename: Gender mismatch: ".$data_array[$id]["gender"]." and $gender";
      $data=fgetcsv($handle);
      continue;
    }
    $data_array[$id]["descriptions"][$lang]=array("language"=>$lang, "value"=>$desc);
    $data=fgetcsv($handle);
  }                                                          
  fclose($handle);
  return $data_array;
}
?>