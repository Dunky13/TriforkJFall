<?php

$id = trim($_POST['id']);
$name = trim($_POST['name']);
$phone = trim($_POST['phone']);
$email = trim($_POST['email']);

if(empty($id) || empty($name) || empty($phone) || empty($email)){
	die();
}

$fileArray = array();
$fp = fopen("nosql.csv", "c+");

while(!flock($fp, LOCK_EX)){
	usleep(200);
}
while(!feof($fp)){
	$fileArray[] = fgets($fp);
}
$csv = array_map("str_getcsv", $fileArray);
$keys = array_shift($csv);

foreach ($csv as $i=>$row) {
	$csv[$i] = array_combine($keys, $row);
}
$foundPrice = false;
$rowData = NULL;

foreach($csv as $key => $row){
	if(strcmp(trim($row["ID"]), $id) == 0){
		$csv[$key]["Full Name"] = $name;
		$csv[$key]["Email"] = $email;
		$csv[$key]["Phone"] = $phone;
		break;
	}
}


$newFile = implode(",",$keys).PHP_EOL;

foreach($csv as $row){
	$newFile .= implode(",", $row).PHP_EOL;
}
rewind($fp);
ftruncate($fp, 0);
fwrite($fp, trim($newFile));

flock($fp, LOCK_UN);

//var_dump($csv);

//echo $newFile;
?>
