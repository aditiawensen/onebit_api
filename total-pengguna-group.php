<?
date_default_timezone_set("Asia/Makassar");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$id_group = $_GET['id_group'];

$q = $con->query("SELECT * FROM t_pengguna_group WHERE id_group='$id_group'")->num_rows;
	
$outp ='[{"jumlah":"'.$q.'"}]';
echo($outp);	

$con->close();
?>