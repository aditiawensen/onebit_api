<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$id_pengaduan = $_GET['id_pengaduan'];
$id_pengguna = $_GET['id_pengguna'];
$id_dukungan = $id_pengguna.$id_pengaduan;

$q = $con->query("DELETE FROM dukungan WHERE id_dukungan='$id_dukungan'");

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