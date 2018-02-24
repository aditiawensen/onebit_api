<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
	
include('aw-config/config.php');

$id_obrolan = $_GET['id_obrolan'];
$id_pengguna = $_GET['id_pengguna'];
$id_love_obrolan = $id_pengguna.$id_obrolan;
	
$q = $con->query("DELETE FROM love_obrolan WHERE id_love_obrolan='$id_love_obrolan'");

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