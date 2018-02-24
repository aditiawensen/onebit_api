<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$id_pengguna = $_GET['id_pengguna'];

$q = $con->query("SELECT * FROM akses_panic_button WHERE id_perangkat='$id_pengguna' AND blokir='N'");

$outp = "";
if ($q->num_rows > 0) {
	$outp .= '{"success":' . 1 . '}';
} else {
	$outp .= '{"success":' . 0 . '}';
}

$outp ='['.$outp.']';
echo($outp);

$con->close();
?>