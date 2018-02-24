<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$id_notifikasi = $_POST['id_notifikasi'];

$q = $con->query("UPDATE notifikasi SET dibaca='Y' WHERE id_notifikasi='$id_notifikasi'");

$outp = "";
if ($con->affected_rows > 0) {
	$outp .= '{"success":' . 1 . '}';
} else {
	$outp .= '{"success":' . 0 . '}';
}

$outp ='['.$outp.']';
echo($outp);

$con->close();
?>