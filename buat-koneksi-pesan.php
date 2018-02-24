<?
date_default_timezone_set("Asia/Makassar");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$time_now = date('Y-m-d H:i:s');
	
include('aw-config/config.php');

$id_pengguna = $_GET['id_pengguna'];
$id_partner = $_GET['id_partner'];

$key = $id_pengguna.$id_partner;

$q = $con->query("SELECT * FROM koneksi_pesan WHERE (id_pengguna='$id_pengguna' AND id_partner='$id_partner') OR (id_pengguna='$id_partner' AND id_partner='$id_pengguna')");

$outp = "";
if ($q->num_rows > 0) {
	$outp .= '{"success":' . 2 . '}';
} else {
	$con->query("INSERT INTO koneksi_pesan(id_koneksi,id_pengguna,id_partner,waktu_buat,id_terbaru,urut) VALUES('$key','$id_pengguna','$id_partner','$time_now','0','0')");
	$con->query("INSERT INTO koneksi_pesan(id_koneksi,id_pengguna,id_partner,waktu_buat,id_terbaru,urut) VALUES('$key','$id_partner','$id_pengguna','$time_now','0','0')");
	if ($con->affected_rows > 0) {
		$outp .= '{"success":' . 1 . '}';
	} else {
		$outp .= '{"success":' . 0 . '}';
	}
}

$outp ='['.$outp.']';
echo($outp);

$con->close();
?>