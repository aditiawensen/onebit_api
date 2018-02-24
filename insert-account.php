<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$id_pengguna = $_GET['id_pengguna'];
$nama_pengguna = $_GET['nama_pengguna'];
$telp_pengguna = $_GET['telp_pengguna'];

$q = $con->query("INSERT INTO pengguna(id_pengguna,nama_pengguna,gambar_pengguna,telp_pengguna,waktu_daftar) VALUES('$id_pengguna','$nama_pengguna','user.png','$telp_pengguna',NOW())");

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