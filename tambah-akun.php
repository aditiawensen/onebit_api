<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$id_pengguna = $_POST['id_pengguna'];
$nama_pengguna = $_POST['nama_pengguna'];
$telp_pengguna = $_POST['telp_pengguna'];
$no_ktp = $_POST['no_ktp'];

$q = $con->query("INSERT INTO pengguna(id_pengguna,nama_pengguna,gambar_pengguna,telp_pengguna,waktu_daftar,no_ktp) VALUES('$id_pengguna','$nama_pengguna','user.png','$telp_pengguna',NOW(),'$no_ktp')");

$outp = "";
if ($con->affected_rows > 0) {
	$outp .= '{"success":' . 1 . '}';
} else {
	$outp .= '{"success":' . 0 . '}';
}

echo($outp);

$con->close();
?>