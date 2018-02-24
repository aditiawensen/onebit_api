<?
date_default_timezone_set("Asia/Makassar");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$id_pengguna = $_GET['id_pengguna'];

$q = $con->query("SELECT * FROM notifikasi WHERE (id_pengguna='$id_pengguna' OR id_pengguna='publik') AND dibaca='N'")->num_rows;
	
$outp ='[{"jumlah":"'.$q.'"}]';
echo($outp);	

$con->close();
?>