<?php

$randomPages = array("https://registreerjedrone.nl", "http://trifork.nl", "http://nu.nl", "http://tweakers.net");
$id = trim($_GET['id']);
$fileArray = array();
$fp = fopen("nosql.csv", "r+");

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
foreach($csv as $row){
	if(strcmp(trim($row["ID"]), $id) == 0){
		$rowData = $row;
		break;
	}
}
flock($fp, LOCK_UN);
?>

<html>
	<head>
		<title>Trifork JFall</title>
	</head>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<body>
		<header style="text-align: center; width: 100%; height: 10%">
			<img src="https://trifork.com/wp-content/uploads/2014/04/logo_trifork.png"/>
		</header >
		<?php
if($rowData != NULL){
	if(empty($rowData["Full Name"]) && empty($rowData["Email"]) && empty($rowData["Phone"])){
		//Check if form data is already filled

		?>

		<div>
			Congratulations You have won: <?php echo $rowData["Price"];?><br/>
			Please fill out the following form
		</div>
		<form method="post" action="#" id="form">
			<input type="hidden" name="id" value="<?php echo $rowData["ID"];?>">
			<input type="text" name="name" placeholder="Full Name">
			<input type="email" name="email" placeholder="Your email adress">
			<input type="tel" name="phone" placeholder="Your Phone Number">
			<input type="submit" value="Submit" id="submit">
		</form>
		<script id="formScript">$(document).ready(function(){
			$("#submit").click(function(e){
				e.preventDefault();
					$.ajax({
						method: "POST",
						url: "submitForm.php",
						data: $("#form").serialize(),
						success: function(data){
							//console.log(data);
							alert("Bedankt voor het mee doen, en gefeliciteerd met je prijs");
							$("#form").remove();
							$("#formScript").remove();
						}
					});

			});
			});
		</script>
		<?php
	}
	else {
		?>

		<div>
			Hmm something might have gone wrong, please scan another QR code!
		</div>

		<?php
	}
}
else{
	echo "Bummer you did not win a price this time";
	header("Refresh: 3; ".$randomPages[array_rand($randomPages)]);
	die();
}
		?>


	</body>
</html>





<?php

//var_dump($csv);
